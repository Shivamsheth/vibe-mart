<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;                // ✅ FIX: correct namespace
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // ✅ FIX: import Hash
use App\Traits\Responses;
use App\Jobs\SendVerificationMail;

class AuthController extends Controller
{
    use Responses;

    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:100',
                'email' => 'required|email|unique:users,email',
                'phone' => 'required|string|min:10|max:15|unique:users,phone',
                'password' => 'required|string|min:8',
                'confirm_password' => 'required|same:password',

                'type' => 'required|in:admin,customer,seller',
                'address' => 'required|string|max:255',
                'city' => 'required|string|max:100',
                'state' => 'required|string|max:100',
                'pincode' => 'required|string|min:4|max:10',
                'country' => 'required|string|max:100',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->validationErrors($e->errors());
        }

        try {
            $otp = rand(100000, 999999);
            $otpExpiresAt = now()->addMinutes(5);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'type' => $request->type ?? 'customer',
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'pincode' => $request->pincode,
                'country' => $request->country,
                'otp' => $otp,
                'otp_expires_at' => $otpExpiresAt,
                'email_verified_at' => false,
            ]);

            SendVerificationMail::dispatch($user, $otp)->onQueue('emails');

            return $this->created([
                'user_id' => $user->id,
                'email'   => $user->email,
            ], 'Registration successful! Please check your email for OTP.');
        } catch (\Exception $e) {
            return $this->error('Registration failed: '.$e->getMessage(), 500);
        }
    }

    public function verifyOtp(Request $request)
    {
    try {
        // 1) Validate input
        $request->validate([
            'email' => 'required|email',         // to identify user
            'otp'   => 'required|digits:6',      // exactly 6 digits
        ]);

        // 2) Find user with this email and not yet verified
        $user = User::where('email', $request->email)
            ->where('email_verified_at', false)
            ->first();

        if (!$user) {
            return $this->error('User not found or already verified', 404);
        }

        // 3) Check OTP
        if ($user->otp !== $request->otp) {
            return $this->error('Invalid OTP', 400);
        }

        // 4) Check expiry
        if ($user->otp_expires_at === null || $user->otp_expires_at->isPast()) {
            return $this->error('OTP has expired, please request a new one.', 400);
        }

        // 5) Mark email as verified and clear OTP
        $user->update([
            'email_verified_at' => true,
            'otp'              => null,
            'otp_expires_at'   => null,
            'otp_attempts'     => 0,
        ]);

        return $this->successMessage('Email verified successfully.');

     } catch (\Illuminate\Validation\ValidationException $e) {
        return $this->validationErrors($e->errors());
        } catch (\Exception $e) {
            return $this->error('Invalid OTP: '.$e->getMessage(), 500);
    }

}

public function resendOtp(Request $request)
{
    try {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)
            ->where('email_verified_at', false)
            ->first();

        if (! $user) {
            return $this->error('User not found or already verified.', 404);
        }

        if ($user->otp_expires_at && $user->otp_expires_at->isFuture()) {
            return $this->error('Previous OTP is still valid. Please wait before requesting a new one.', 400);
        }

        $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $otpExpiresAt = now()->addMinutes(5);

        $user->update([
            'otp' => $otp,
            'otp_expires_at' => $otpExpiresAt,
        ]);

        SendVerificationMail::dispatch($user, $otp); 

        return $this->successMessage('A new OTP has been sent to your email.');

    } catch (\Illuminate\Validation\ValidationException $e) {
        return $this->validationErrors($e->errors());
    } catch (\Exception $e) {
        return $this->error('Failed to resend OTP: '.$e->getMessage(), 500);
    }
}

        public function login(Request $request)
{
    try {
        $request->validate([
            'email'    => 'required|string',   // email or phone
            'password' => 'required|string',
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return $this->validationErrors($e->errors());
    }

    try {
        $user = User::where('email', $request->email)
            ->orWhere('phone', $request->email)
            ->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return $this->unauthorized('Invalid credentials.');
        }

        if (! $user->email_verified_at) {
            return $this->error('Please verify your email first.', 403);
        }

        if (! $user->is_active) {
            return $this->error('Account is deactivated.', 403);
        }

        $user->update(['last_login_at' => now()]);

        $token = $user->createToken('auth-token')->plainTextToken;

        // Decide where frontend should redirect
        $redirectTo = $user->type === 'admin'
            ? route('admin.dashboard')   // admin panel
            : route('home');             // customer/seller home (define later)

        return $this->success([
            'user'        => $user,
            'token'       => $token,
            'token_type'  => 'Bearer',
            'redirect_to' => $redirectTo,   // <– important
        ], 'Login successful.');
    } catch (\Exception $e) {
        return $this->error('Login failed: '.$e->getMessage(), 500);
    }
}


}
