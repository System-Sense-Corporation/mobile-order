<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Available roles for administrative user management.
     *
     * @var array<int, string>
     */
    private const ROLES = ['admin', 'manager', 'staff'];

    /**
     * Display a listing of the users.
     */
    public function index(): View
    {
        $users = User::orderByDesc('created_at')->paginate(10);

        return view('admin.users.index', [
            'users' => $users,
        ]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        return view('admin.users.create', [
            'roles' => $this->availableRoles(),
        ]);
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $availableRoles = array_keys($this->availableRoles());

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in($availableRoles)],
        ]);

        User::create($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('status', __('messages.admin.users.messages.created'));
    }

    /**
     * Get the roles available for assignment mapped to translated labels.
     *
     * @return array<string, string>
     */
    private function availableRoles(): array
    {
        return collect(self::ROLES)
            ->mapWithKeys(fn (string $role) => [
                $role => Lang::get('messages.admin.users.roles.' . $role),
            ])
            ->all();
    }
}
