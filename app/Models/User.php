<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
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
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'email_verification_code_expires_at' => 'datetime',
        'password_reset_code_expires_at' => 'datetime',
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
}
