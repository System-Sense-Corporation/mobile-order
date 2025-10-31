<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderCreatePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_page_displays_seeded_options(): void
    {
        $this->seed();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('orders.create'));

        $response
            ->assertOk()
            ->assertSee('鮮魚酒場 波しぶき', false)
            ->assertSee('本マグロ 柵 500g', false);
    }
}
