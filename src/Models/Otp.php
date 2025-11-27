<?php

namespace Rawnoq\Otp\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Otp extends Model
{
    use HasFactory;

    protected $table = 'otp_verifications';

    protected $fillable = [
        'identifier',
        'type',
        'otp_code',
        'expires_at',
        'is_used',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_used' => 'boolean',
    ];

    public function isValid(): bool
    {
        return !$this->is_used && $this->expires_at->isFuture();
    }

    public function markAsUsed(): bool
    {
        return $this->update(['is_used' => true]);
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }
}

