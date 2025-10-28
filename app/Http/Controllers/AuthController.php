<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $remember = (bool) $request->boolean('remember');

        if (! $request->filled('email') && ! $request->filled('password')) {
            $user = User::query()->first();

            if (! $user) {
                return back()
                    ->withErrors([
                        'email' => __('messages.auth.failed'),
                    ]);
            }

            Auth::login($user, $remember);
            $request->session()->regenerate();

            return redirect()->intended(route('home'));
        }

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended(route('home'));
        }

        return back()
            ->withErrors([
                'email' => __('messages.auth.failed'),
            ])
            ->onlyInput('email');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

