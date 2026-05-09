<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

   public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();
    $request->session()->regenerate();

    $user = auth()->user();

    // Staff go straight to dashboard — no email verification required
    if ($user->isAdmin() || $user->isCoordinator()) {
        return redirect()->intended(route('admin.dashboard'));
    }

    // Alumni must verify email first
    if (!$user->hasVerifiedEmail()) {
        // intended() will redirect back to the verify link they clicked
        return redirect()->intended(route('verification.notice'));
    }

    return redirect()->intended(route('alumni.profile'));
}

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}