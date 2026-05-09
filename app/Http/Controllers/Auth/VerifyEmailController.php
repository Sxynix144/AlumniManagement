<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            if ($user->isAdmin() || $user->isCoordinator()) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('alumni.profile')->with('success', 'Email already verified.');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        if ($user->isAdmin() || $user->isCoordinator()) {
            return redirect()->route('admin.dashboard')->with('success', 'Email verified! Welcome back.');
        }

        return redirect()->route('alumni.profile')->with('success', 'Email verified! Your account is now active.');
    }
}