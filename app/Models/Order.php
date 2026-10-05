<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'channel',
        'status',
        'created_by',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function adjustments(): HasMany
    {
        return $this->hasMany(OrderAdjustment::class);
    }

    public function pendingAdjustment(): HasOne
    {
        return $this->hasOne(OrderAdjustment::class)->where('status', 'pending');
    }

    public function hasPendingAdjustment(): bool
    {
        return $this->adjustments()->where('status', 'pending')->exists();
    }

    public function canBeAdjusted(): bool
    {
        return !in_array($this->status, ['exported', 'cancelled']);
    }
}
