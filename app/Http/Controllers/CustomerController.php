<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Support\DemoCustomerData;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CustomerController extends Controller
{
    public function index(): View
    {
        if (Schema::hasTable('customers')) {
            DemoCustomerData::ensureInDatabase();
            $customers = Customer::query()->orderBy('name')->get();
            $customersAreDemo = DemoCustomerData::namesMatch($customers->pluck('name'));
        } else {
            $customers = DemoCustomerData::sample();
            $customersAreDemo = true;
        }

        return view('customers', [
            'customers' => $customers,
            'customersAreDemo' => $customersAreDemo,
        ]);
    }

    public function create(): View
    {
        return view('customers.form', [
            'customer' => new Customer(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (! Schema::hasTable('customers')) {
            abort(500, 'Customers table is not available.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'contact' => ['nullable', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $customer = Customer::create([
            'name' => $data['name'],
            'contact' => $data['contact'] ?? null,
            'contact_person' => $data['contact_person'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);

        return redirect()
            ->route('customers')
            ->with('status', __('messages.customers.flash.saved', ['name' => $customer->name]));
    }
}
