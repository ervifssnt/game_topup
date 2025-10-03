<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'username',
        'email',
        'phone',
        'password_hash',
        'balance',
        'is_admin',
        'is_locked',
        'locked_at',
        'locked_reason',
        'failed_login_attempts',
    ];

    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
    ];

    // Override default password column
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    // Relationships
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Helper methods
    public function hasEnoughBalance($amount)
    {
        return $this->balance >= $amount;
    }

    public function deductBalance($amount)
    {
        $this->balance -= $amount;
        $this->save();
    }

    public function addBalance($amount)
    {
        $this->balance += $amount;
        $this->save();
    }

    // Lock account
    public function lockAccount($reason = 'Too many failed login attempts')
    {
        $this->is_locked      = true;
        $this->locked_at      = now();
        $this->locked_reason  = $reason;
        $this->save();

        AuditLog::log(
            'account_locked',
            "Account locked: {$this->username} - Reason: {$reason}",
            'User',
            $this->id
        );
    }

    // Unlock account
    public function unlockAccount()
    {
        $this->is_locked             = false;
        $this->locked_at             = null;
        $this->locked_reason         = null;
        $this->failed_login_attempts = 0;
        $this->save();

        AuditLog::log(
            'account_unlocked',
            "Account unlocked: {$this->username}",
            'User',
            $this->id
        );
    }

    // Increment failed attempts
    public function incrementFailedAttempts()
    {
        $this->failed_login_attempts++;
        $this->save();

        // Lock after 5 failed attempts
        if ($this->failed_login_attempts >= 5) {
            $this->lockAccount();
        }
    }

    // Reset failed attempts
    public function resetFailedAttempts()
    {
        $this->failed_login_attempts = 0;
        $this->save();
    }

    // Check if account is locked
    public function isLocked()
    {
        // Auto-unlock after 30 minutes
        if ($this->is_locked && $this->locked_at) {
            if (now()->diffInMinutes($this->locked_at) >= 30) {
                $this->unlockAccount();
                return false;
            }
        }

        return $this->is_locked;
    }
}
