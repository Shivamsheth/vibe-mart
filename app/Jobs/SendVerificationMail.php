<?php

namespace App\Jobs;

use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log; // ✅ ADD THIS

class SendVerificationMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 30;
    public array $backoff = [10, 30, 60];

    public User $user;
    public string $otp;

    public function __construct(User $user, string $otp)
    {
        $this->user = $user;
        $this->otp  = $otp;
        $this->onQueue('emails'); // queue name = emails
    }

    public function handle(): void
    {
        Mail::to($this->user->email)
            ->send(new OtpMail($this->user, $this->otp));

        Log::info("✅ OTP email sent to: {$this->user->email}");
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("❌ OTP email FAILED permanently for user {$this->user->id}", [
            'email' => $this->user->email,
            'error' => $exception->getMessage(),
        ]);
    }
}
