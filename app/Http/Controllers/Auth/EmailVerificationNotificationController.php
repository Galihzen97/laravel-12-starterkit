<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
   public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false))
                ->with('status', 'Email already verified.');
        }

        try {
            $request->user()->sendEmailVerificationNotification();
            return back()->with('status', 'Verification link sent. Please check your email or spam folder.');
        } catch (\Exception $e) {
            Log::error('Failed to send verification email: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Failed to send verification email. Please try again later.']);
        }
    }


}
