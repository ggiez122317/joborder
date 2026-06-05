<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\PdsDataService;
use App\Services\PdsFileParser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Mockery;
use Tests\TestCase;

class AuthAndUserUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_login_with_plain_username(): void
    {
        $user = User::query()->create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('secret123'),
            'role' => 'admin',
        ]);
        $user->forceFill(['email_verified_at' => now()])->save();

        $response = $this->post('/login', [
            'login' => 'admin',
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_admin_login_ignores_stale_user_intended_route(): void
    {
        $user = User::query()->create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('secret123'),
            'role' => 'admin',
        ]);
        $user->forceFill(['email_verified_at' => now()])->save();

        $response = $this->withSession(['url.intended' => route('user.dashboard')])->post('/login', [
            'login' => 'admin',
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_page_uses_single_unified_screen(): void
    {
        $response = $this->get(route('login'));

        $response->assertOk();
        $response->assertSee('Sign in to continue');
        $response->assertSee('Username or Email');
        $response->assertDontSee('Switch here');
    }

    public function test_verified_user_can_open_test_upload_screen(): void
    {
        $user = User::query()->create([
            'name' => 'Portal User',
            'username' => 'portaluser',
            'email' => 'user@example.com',
            'password' => Hash::make('secret123'),
            'role' => 'user',
        ]);
        $user->forceFill(['email_verified_at' => now()])->save();

        $response = $this->actingAs($user)->get(route('user.pds.upload'));

        $response->assertOk();
        $response->assertSee('Test Upload Mockup PDS');
    }

    public function test_admin_can_open_users_management_page(): void
    {
        $admin = User::query()->create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('secret123'),
            'role' => 'admin',
        ]);
        $admin->forceFill(['email_verified_at' => now()])->save();

        $response = $this->actingAs($admin)->get(route('admin.users.index'));

        $response->assertOk();
        $response->assertSee('Create User');
        $response->assertSee('User List');
    }

    public function test_verified_user_can_upload_mockup_pds_for_review(): void
    {
        $user = User::query()->create([
            'name' => 'Portal User',
            'username' => 'portaluser',
            'email' => 'user@example.com',
            'password' => Hash::make('secret123'),
            'role' => 'user',
        ]);
        $user->forceFill(['email_verified_at' => now()])->save();

        $parsed = app(PdsDataService::class)->defaultData([
            'personal' => [
                'surname' => 'Dela Cruz',
                'first_name' => 'Juan',
            ],
        ]);

        $mock = Mockery::mock(PdsFileParser::class);
        $mock->shouldReceive('parse')
            ->once()
            ->andReturn($parsed);
        $this->app->instance(PdsFileParser::class, $mock);

        $response = $this->actingAs($user)->post(route('user.pds.upload.parse'), [
            'pds_file' => UploadedFile::fake()->create('mockup.pdf', 120, 'application/pdf'),
        ]);

        $response->assertOk();
        $response->assertSee('Test upload mapped the file into the form');
        $response->assertSee('Dela Cruz');
        $response->assertSee('Juan');
    }
}
