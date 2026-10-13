<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class BackupTrashTest extends TestCase
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

    private function makeEmployee(array $overrides = []): Employee
    {
        return Employee::query()->create(array_merge([
            'full_name' => 'Juan Dela Cruz',
            'surname' => 'Dela Cruz',
            'first_name' => 'Juan',
            'position_title' => 'Clerk',
            'office' => 'HRMO',
            'is_active' => true,
        ], $overrides));
    }

    public function test_admin_soft_delete_moves_record_to_backup_and_can_restore(): void
    {
        $admin = $this->makeAdmin();
        $employee = $this->makeEmployee(['full_name' => 'Restore Me']);

        // Soft delete via admin route
        $response = $this->actingAs($admin)->delete(route('pds.destroy', $employee));
        $response->assertRedirect(route('records.index'));

        $this->assertSoftDeleted('employees', ['id' => $employee->id]);

        // Hidden from normal index
        $index = $this->actingAs($admin)->get(route('records.index'));
        $index->assertOk();
        $index->assertDontSee('Restore Me');

        // Visible in backup trash
        $trash = $this->actingAs($admin)->get(route('backups.index'));
        $trash->assertOk();
        $trash->assertSee('Restore Me');

        // Restore
        $restore = $this->actingAs($admin)->post(route('backups.restore', $employee->id));
        $restore->assertRedirect(route('backups.index'));

        $this->assertDatabaseHas('employees', ['id' => $employee->id, 'deleted_at' => null]);

        // Permanent delete after soft-deleting again
        $this->actingAs($admin)->delete(route('pds.destroy', $employee));
        $force = $this->actingAs($admin)->delete(route('backups.destroy', $employee->id));
        $force->assertRedirect(route('backups.index'));
        $this->assertDatabaseMissing('employees', ['id' => $employee->id]);
    }

    public function test_records_index_paginates_ten_per_page(): void
    {
        $admin = $this->makeAdmin();

        for ($i = 1; $i <= 11; $i++) {
            $this->makeEmployee(['full_name' => 'Employee ' . $i]);
        }

        $response = $this->actingAs($admin)->get(route('records.index'));
        $response->assertOk();

        /** @var \Illuminate\Pagination\LengthAwarePaginator $paginator */
        $paginator = $response->viewData('employees');

        $this->assertEquals(10, $paginator->perPage());
        $this->assertEquals(10, $paginator->count());
        $this->assertEquals(11, $paginator->total());
    }

    public function test_user_trash_is_scoped_to_own_records(): void
    {
        $userA = User::query()->create([
            'name' => 'User A', 'username' => 'usera', 'email' => 'a@example.com',
            'password' => Hash::make('secret123'), 'role' => 'user',
        ]);
        $userA->forceFill(['email_verified_at' => now()])->save();

        $userB = User::query()->create([
            'name' => 'User B', 'username' => 'userb', 'email' => 'b@example.com',
            'password' => Hash::make('secret123'), 'role' => 'user',
        ]);
        $userB->forceFill(['email_verified_at' => now()])->save();

        $employee = $this->makeEmployee(['full_name' => 'UserA Record', 'created_by' => $userA->id]);

        $this->actingAs($userA)->delete(route('user.records.destroy', $employee));
        $this->assertSoftDeleted('employees', ['id' => $employee->id]);

        // Owner sees it in trash
        $trash = $this->actingAs($userA)->get(route('user.records.trash'));
        $trash->assertOk();
        $trash->assertSee('UserA Record');

        // Other user cannot restore it
        $this->actingAs($userB)->post(route('user.records.restore', $employee->id))->assertNotFound();
    }
}
