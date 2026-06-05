<?php

namespace App\Models;

use App\Models\UserTrustedDevice;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;

use App\Traits\Auditable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, Auditable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'office',
        'email_verification_code',
        'email_verification_code_expires_at',
        'password_reset_code',
        'password_reset_code_expires_at',
        'two_factor_code',
        'two_factor_code_expires_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'email_verification_code_expires_at' => 'datetime',
        'password_reset_code_expires_at' => 'datetime',
        'two_factor_code_expires_at' => 'datetime',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function generateEmailVerificationCode(int $minutes = 10): string
    {
        $code = (string) random_int(100000, 999999);

        $this->forceFill([
            'email_verification_code' => hash('sha256', $code),
            'email_verification_code_expires_at' => now()->addMinutes($minutes),
        ])->save();

        return $code;
    }

    public function hasValidEmailVerificationCode(string $code): bool
    {
        if ($this->email_verification_code === null || $this->email_verification_code_expires_at === null) {
            return false;
        }

        if ($this->email_verification_code_expires_at->isPast()) {
            return false;
        }

        return hash_equals($this->email_verification_code, hash('sha256', trim($code)));
    }

    public function clearEmailVerificationCode(): void
    {
        $this->forceFill([
            'email_verification_code' => null,
            'email_verification_code_expires_at' => null,
        ])->save();
    }

    public function generatePasswordResetCode(int $minutes = 10): string
    {
        $code = (string) random_int(100000, 999999);

        $this->forceFill([
            'password_reset_code' => hash('sha256', $code),
            'password_reset_code_expires_at' => now()->addMinutes($minutes),
        ])->save();

        return $code;
    }

    public function hasValidPasswordResetCode(string $code): bool
    {
        if ($this->password_reset_code === null || $this->password_reset_code_expires_at === null) {
            return false;
        }

        if ($this->password_reset_code_expires_at->isPast()) {
            return false;
        }

        return hash_equals($this->password_reset_code, hash('sha256', trim($code)));
    }

    public function clearPasswordResetCode(): void
    {
        $this->forceFill([
            'password_reset_code' => null,
            'password_reset_code_expires_at' => null,
        ])->save();
    }

    public function generateTwoFactorCode(int $minutes = 5): string
    {
        $code = (string) random_int(100000, 999999);

        $this->forceFill([
            'two_factor_code' => hash('sha256', $code),
            'two_factor_code_expires_at' => now()->addMinutes($minutes),
        ])->save();

        return $code;
    }

    public function hasValidTwoFactorCode(string $code): bool
    {
        if ($this->two_factor_code === null || $this->two_factor_code_expires_at === null) {
            return false;
        }

        if ($this->two_factor_code_expires_at->isPast()) {
            return false;
        }

        return hash_equals($this->two_factor_code, hash('sha256', trim($code)));
    }

    public function clearTwoFactorCode(): void
    {
        $this->forceFill([
            'two_factor_code' => null,
            'two_factor_code_expires_at' => null,
        ])->save();
    }

    public function trustCurrentDevice(Request $request): string
    {
        $token = bin2hex(random_bytes(32));

        UserTrustedDevice::create([
            'user_id' => $this->id,
            'token_hash' => hash('sha256', $token),
            'user_agent' => $request->userAgent(),
            'ip_address' => $request->ip(),
            'last_used_at' => now(),
        ]);

        return $token;
    }

    public function isCurrentDeviceTrusted(string $token, ?string $userAgent = null): bool
    {
        $hash = hash('sha256', $token);

        return UserTrustedDevice::where('user_id', $this->id)
            ->where('token_hash', $hash)
            ->when($userAgent, fn ($q) => $q->where('user_agent', $userAgent))
            ->exists();
    }
}
