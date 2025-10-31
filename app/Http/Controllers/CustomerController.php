<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Throwable;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $customers = Customer::query()->orderBy('name')->get();

        return view('customers', [
            'customers' => $customers,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'contact' => ['nullable', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        try {
            Customer::create($data);

            return redirect()
                ->route('customers.index')
                ->with('status', __('messages.customers.flash.created'));
        } catch (Throwable $throwable) {
            report($throwable);

            return redirect()
                ->route('customers.index')
                ->with('error', __('messages.customers.flash.create_failed'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'contact' => ['nullable', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        try {
            $customer->update($data);

            return redirect()
                ->route('customers.index')
                ->with('status', __('messages.customers.flash.updated'));
        } catch (Throwable $throwable) {
            report($throwable);

            return redirect()
                ->route('customers.index')
                ->with('error', __('messages.customers.flash.update_failed'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer): RedirectResponse
    {
        try {
            $customer->delete();

            return redirect()
                ->route('customers.index')
                ->with('status', __('messages.customers.flash.deleted'));
        } catch (Throwable $throwable) {
            report($throwable);

            return redirect()
                ->route('customers.index')
                ->with('error', __('messages.customers.flash.delete_failed'));
        }
    }
}
