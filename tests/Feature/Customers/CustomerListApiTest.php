<?php

namespace Tests\Feature\Customers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerListApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_fetch_customers_as_json(): void
    {
        $user = User::factory()->create();
        $customers = Customer::factory()->count(2)->create();

        $response = $this->actingAs($user)->getJson(route('customers'));

        $response->assertOk();
        $response->assertJson(['demo' => false]);
        $response->assertJsonCount(2, 'data');
        $response->assertJsonFragment([
            'id' => (string) $customers[0]->id,
            'name' => $customers[0]->name,
        ]);
        $response->assertJsonFragment([
            'id' => (string) $customers[1]->id,
            'name' => $customers[1]->name,
        ]);
    }
}
