<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Throwable;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        if (Schema::hasTable('customers')) {
            $this->ensureDemoCustomersInDatabase();

            $customers = Customer::query()->orderBy('name')->get();
            $customersAreDemo = $this->listMatchesDemo(
                $customers->pluck('name'),
                collect($this->demoCustomerDefinitions())->pluck('name')->all()
            );
        } else {
            $customers = $this->sampleCustomers();
            $customersAreDemo = true;
        }

        return view('customers.index', [
            'customers' => $customers,
            'customersAreDemo' => $customersAreDemo,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('customers.form', [
            'customer' => new Customer(),
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
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer): View
    {
        return view('customers.form', [
            'customer' => $customer,
        ]);
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

    private function sampleCustomers(): Collection
    {
        return collect($this->demoCustomerDefinitions())
            ->values()
            ->map(static function (array $customer, int $index): object {
                return (object) array_merge($customer, [
                    'id' => $index + 1,
                ]);
            });
    }

    /**
     * @return array<int, array<string, string|null>>
     */
    private function demoCustomerDefinitions(): array
    {
        return [
            [
                'name' => '鮮魚酒場 波しぶき',
                'contact' => '03-1234-5678',
                'contact_person' => '山田様',
                'notes' => 'Deliver every morning at 8:00',
            ],
            [
                'name' => 'レストラン 潮彩',
                'contact' => '045-432-1111',
                'contact_person' => '佐藤シェフ',
                'notes' => 'Prefers premium white fish',
            ],
            [
                'name' => 'ホテル ブルーサンズ',
                'contact' => '0467-222-0099',
                'contact_person' => '購買部 三浦様',
                'notes' => 'Places bulk orders regularly',
            ],
        ];
    }

    private function ensureDemoCustomersInDatabase(): void
    {
        if (! Schema::hasTable('customers') || Customer::query()->exists()) {
            return;
        }

        foreach ($this->demoCustomerDefinitions() as $customer) {
            Customer::create([
                'name' => $customer['name'],
                'contact' => $customer['contact'] ?? null,
                'contact_person' => $customer['contact_person'] ?? null,
                'notes' => $customer['notes'] ?? null,
            ]);
        }
    }

    /**
     * @param  array<int, string>  $demoNames
     */
    private function listMatchesDemo(Collection $names, array $demoNames): bool
    {
        if ($names->isEmpty()) {
            return false;
        }

        return $names
            ->sort()
            ->values()
            ->all() === collect($demoNames)
            ->sort()
            ->values()
            ->all();
    }
}
