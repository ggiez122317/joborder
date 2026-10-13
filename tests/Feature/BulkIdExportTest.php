<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class BulkIdExportTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(): User
    {
        $user = User::query()->create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('secret123'),
            'role' => 'admin',
        ]);
        $user->forceFill(['email_verified_at' => now()])->save();

        return $user;
    }

    public function test_batch_id_cards_accepts_office_scope(): void
    {
        $admin = $this->makeAdmin();

        Employee::query()->create([
            'full_name' => 'Office A Person', 'first_name' => 'Office', 'surname' => 'ALPHAONE',
            'office' => 'ACCOUNTING', 'is_active' => true,
        ]);
        Employee::query()->create([
            'full_name' => 'Office B Person', 'first_name' => 'Office', 'surname' => 'BETAONE',
            'office' => 'HRMO', 'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('pds.records.batch-id-cards', ['office' => 'ACCOUNTING']));

        $response->assertOk();
        $response->assertSee('ALPHAONE');
        $response->assertDontSee('BETAONE');
        $response->assertSee('Download Combined PDF', false);
    }

    public function test_batch_id_cards_still_accepts_selected_ids(): void
    {
        $admin = $this->makeAdmin();

        $one = Employee::query()->create([
            'full_name' => 'Selected One', 'first_name' => 'Selected', 'surname' => 'GAMMAONE',
            'office' => 'HRMO', 'is_active' => true,
        ]);
        Employee::query()->create([
            'full_name' => 'Not Selected', 'first_name' => 'Not', 'surname' => 'DELTAONE',
            'office' => 'HRMO', 'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get(
            route('pds.records.batch-id-cards') . '?ids[]=' . $one->id
        );

        $response->assertOk();
        $response->assertSee('GAMMAONE');
        $response->assertDontSee('DELTAONE');
    }

    public function test_batch_id_cards_rejects_empty_request(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)->get(route('pds.records.batch-id-cards'));

        $response->assertRedirect();
    }

    public function test_batch_id_cards_side_front_hides_back_details(): void
    {
        $admin = $this->makeAdmin();

        Employee::query()->create([
            'full_name' => 'Side Test', 'first_name' => 'Side', 'surname' => 'EPSILONONE',
            'office' => 'ACCOUNTING', 'is_active' => true,
        ]);

        $front = $this->actingAs($admin)->get(
            route('pds.records.batch-id-cards', ['office' => 'ACCOUNTING', 'side' => 'front'])
        );
        $front->assertOk();
        $front->assertSee('EPSILONONE');
        $front->assertDontSee('IN CASE OF EMERGENCY');

        $back = $this->actingAs($admin)->get(
            route('pds.records.batch-id-cards', ['office' => 'ACCOUNTING', 'side' => 'back'])
        );
        $back->assertOk();
        $back->assertSee('IN CASE OF EMERGENCY');

        $both = $this->actingAs($admin)->get(
            route('pds.records.batch-id-cards', ['office' => 'ACCOUNTING'])
        );
        $both->assertOk();
        $both->assertSee('EPSILONONE');
        $both->assertSee('IN CASE OF EMERGENCY');
    }

    public function test_export_dropdown_lists_offices_actually_in_data(): void
    {
        $admin = $this->makeAdmin();

        Employee::query()->create([
            'full_name' => 'Custom Office', 'first_name' => 'Custom', 'surname' => 'ZETAONE',
            'office' => 'MUNICIPALITY OF TRENTO', 'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('records.index'));

        $response->assertOk();
        $response->assertSee('MUNICIPALITY OF TRENTO', false);
    }

    public function test_batch_id_cards_qr_toggle(): void
    {
        $admin = $this->makeAdmin();

        Employee::query()->create([
            'full_name' => 'QR Toggle', 'first_name' => 'Qr', 'surname' => 'MUONE',
            'office' => 'ACCOUNTING', 'is_active' => true, 'qr_code_path' => 'qr/sample.svg',
        ]);

        $with = $this->actingAs($admin)->get(
            route('pds.records.batch-id-cards', ['office' => 'ACCOUNTING'])
        );
        $with->assertOk();
        $with->assertSee('<div class="qr-container">', false);

        $without = $this->actingAs($admin)->get(
            route('pds.records.batch-id-cards', ['office' => 'ACCOUNTING', 'qr' => '0'])
        );
        $without->assertOk();
        $without->assertDontSee('<div class="qr-container">', false);
    }

    public function test_records_index_filters_by_office_server_side(): void
    {
        $admin = $this->makeAdmin();

        Employee::query()->create([
            'full_name' => 'KAPPAONE FILTERSON', 'first_name' => 'Kappaone', 'surname' => 'Filterson',
            'office' => 'ACCOUNTING', 'is_active' => true,
        ]);
        Employee::query()->create([
            'full_name' => 'LAMBDAONE OTHERSON', 'first_name' => 'Lambdaone', 'surname' => 'Otherson',
            'office' => 'HRMO', 'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('records.index', ['office' => 'ACCOUNTING']));

        $response->assertOk();
        $response->assertSee('KAPPAONE FILTERSON');
        $response->assertDontSee('LAMBDAONE OTHERSON');
        $this->assertEquals(1, $response->viewData('employees')->total());
    }

    public function test_records_index_provides_office_options_for_export(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)->get(route('records.index'));

        $response->assertOk();
        $response->assertSee('Export Office IDs', false);
        $response->assertSee('Export Selected IDs (PDF)', false);
    }
}
