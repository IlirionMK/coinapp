<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use App\Models\User;

class EmailVerificationController extends Controller
{
    public function __invoke(Request $request, $id, $hash)
    {
        if (!URL::hasValidSignature($request)) {
            return redirect('/403');
        }

        $user = User::find($id);

        if (!$user || sha1($user->getEmailForVerification()) !== $hash) {
            return redirect('/403');
        }

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));

            Log::info('Email verified', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'time' => now()->toDateTimeString(),
            ]);
        }

        Auth::login($user);

        return redirect('/email-verified');
    }
}
