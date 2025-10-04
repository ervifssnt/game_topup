<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResetRequest extends Model
{
    protected $fillable = [
        'user_id',
        'reason',
        'status',
        'processed_by',
        'admin_notes',
        'processed_at',
    ];

    protected $casts = [
        'processed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}