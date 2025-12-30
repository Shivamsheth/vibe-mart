<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendVerificationMail;
use App\Models\User;
use App\Traits\Responses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use Responses;

    public function register(Request $request)
    {
        try {
            $request->validate([
                'name'             => 'required|string|max:100',
                'email'            => 'required|email|unique:users,email',
                'phone'            => 'required|string|min:10|max:15|unique:users,phone',
                'password'         => 'required|string|min:8',
                'confirm_password' => 'required|same:password',

                'type'    => 'required|in:admin,customer,seller',
                'address' => 'required|string|max:255',
                'city'    => 'required|string|max:100',
                'state'   => 'required|string|max:100',
                'pincode' => 'required|string|min:4|max:10',
                'country' => 'required|string|max:100',
            ]);
        } catch (ValidationException $e) {
            return $this->validationErrors($e->errors());
        }

        try {
            if ($request->type === 'admin') {
                $activeAdminExists = User::where('type', 'admin')
                    ->where('is_active', true)
                    ->exists();

                if ($activeAdminExists) {
                    return $this->error(
                        'An active admin already exists. New admin registrations are not allowed.',
                        403
                    );
                }
            }

            $otp          = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $otpExpiresAt = now()->addMinutes(5);

            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'phone'    => $request->phone,
                'password' => Hash::make($request->password),
                'type'     => $request->type ?? 'customer',
                'address'  => $request->address,
                'city'     => $request->city,
                'state'    => $request->state,
                'pincode'  => $request->pincode,
                'country'  => $request->country,
                'otp'             => $otp,
                'otp_expires_at'  => $otpExpiresAt,
                'email_verified_at' => null,
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
            $request->validate([
                'email' => 'required|email',
                'otp'   => 'required|digits:6',
            ]);

            $user = User::where('email', $request->email)
                ->whereNull('email_verified_at')
                ->first();

            if (! $user) {
                return $this->error('User not found or already verified', 404);
            }

            if ($user->otp !== $request->otp) {
                return $this->error('Invalid OTP', 400);
            }

            if ($user->otp_expires_at === null || $user->otp_expires_at->isPast()) {
                return $this->error('OTP has expired, please request a new one.', 400);
            }

            $user->update([
                'email_verified_at' => now(),
                'otp'               => null,
                'otp_expires_at'    => null,
                'otp_attempts'      => 0,
            ]);

            return $this->successMessage('Email verified successfully.');
        } catch (ValidationException $e) {
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
                ->whereNull('email_verified_at')
                ->first();

            if (! $user) {
                return $this->error('User not found or already verified.', 404);
            }

            if ($user->otp_expires_at && $user->otp_expires_at->isFuture()) {
                return $this->error('Previous OTP is still valid. Please wait before requesting a new one.', 400);
            }

            $otp          = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $otpExpiresAt = now()->addMinutes(5);

            $user->update([
                'otp'            => $otp,
                'otp_expires_at' => $otpExpiresAt,
            ]);

            SendVerificationMail::dispatch($user, $otp)->onQueue('emails');

            return $this->successMessage('A new OTP has been sent to your email.');
        } catch (ValidationException $e) {
            return $this->validationErrors($e->errors());
        } catch (\Exception $e) {
            return $this->error('Failed to resend OTP: '.$e->getMessage(), 500);
        }
    }

public function login(Request $request)
{
    $credentials = $request->validate([
        'email'    => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();

        $user  = Auth::guard('web')->user();
        $token = $user->createToken('api-token')->plainTextToken;

        if($user->type == 'admin'){
            return response()->json([
            'success' => true,
            'message' => 'Login successful.',
            'data' => [
                'user'        => $user,
                'token'       => $token,
                'redirect_to' => route('admin.dashboard'),
            ],
        ]);

        }



        if($user->type == 'seller'){
        return response()->json([
            'success' => true,
            'message' => 'Login successful.',
            'data' => [
                'user'        => $user,
                'token'       => $token,
                'redirect_to' => route('home'),
            ],
        ]);

        }

            return response()->json([
            'success' => true,
            'message' => 'Login successful.',
            'data' => [
                'user'        => $user,
                'token'       => $token,
                'redirect_to' => route('home'),
            ],
        ]);


    }

    return response()->json([
        'success' => false,
        'message' => 'Invalid credentials.',
    ], 422);
}



       public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('success', 'Logged out successfully!');
    }

}
