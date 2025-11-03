<?php

namespace Tests\Feature\Customers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerEditingTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_displays_edit_button_that_links_to_form(): void
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create();

        $response = $this->actingAs($user)->get(route('customers'));

        $response->assertOk();
        $response->assertSee(route('customers.form', ['customer' => $customer->id]), false);
        $response->assertSee(__('messages.customers.actions.edit'));
    }

    public function test_form_route_prefills_existing_customer_when_requested(): void
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create([
            'name' => 'Acme Industries',
            'contact' => '0123456789',
            'contact_person' => 'Jane Doe',
            'notes' => 'Priority account',
        ]);

        $response = $this->actingAs($user)->get(route('customers.form', ['customer' => $customer->id]));

        $response->assertOk();
        $response->assertViewIs('customers.form');
        $response->assertViewHas('customer', function (Customer $viewCustomer) use ($customer): bool {
            return $viewCustomer->is($customer);
        });
        $response->assertSee('value="' . e($customer->name) . '"', false);
        $response->assertSee('value="' . e($customer->contact) . '"', false);
        $response->assertSee('value="' . e($customer->contact_person) . '"', false);
        $response->assertSee(e($customer->notes), false);
        $response->assertSee('action="' . route('customers.update', $customer) . '"', false);
    }
}
