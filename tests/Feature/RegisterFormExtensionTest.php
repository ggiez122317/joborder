<?php

namespace Tests\Feature;

use App\Models\RegistrationLink;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterFormExtensionTest extends TestCase
{
    use RefreshDatabase;

    public function test_extension_field_is_a_fixed_dropdown(): void
    {
        $link = RegistrationLink::query()->create([
            'token' => 'test-token-123',
            'label' => 'Test batch',
            'expires_at' => now()->addDays(7),
            'max_uses' => 10,
        ]);

        $response = $this->get(route('register.form', $link));

        $response->assertOk();
        $response->assertSee('<select id="name_extension"', false);
        $response->assertSee('<option value="JR"', false);
        $response->assertSee('<option value="III"', false);
        $response->assertDontSee('<input id="name_extension"', false);
    }
}
