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
}