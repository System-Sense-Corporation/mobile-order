<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlankLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_blank_credentials_auto_log_in_default_user(): void
    {
        $defaultUser = User::factory()->create();

        $response = $this->post(route('login.store'), [
            'email' => '',
            'password' => '',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($defaultUser);
    }
}
