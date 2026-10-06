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

    /**
     * Tối ưu hóa truy vấn: nếu quan hệ adjustments đã được nạp sẵn trong bộ nhớ,
     * trích xuất trực tiếp bản ghi pending thay vì bắn thêm truy vấn DB N+1.
     */
    public function getPendingAdjustmentAttribute(): ?OrderAdjustment
    {
        if ($this->relationLoaded('adjustments')) {
            return $this->adjustments->firstWhere('status', 'pending');
        }

        if ($this->relationLoaded('pendingAdjustment')) {
            return $this->getRelationValue('pendingAdjustment');
        }

        return $this->getRelationValue('pendingAdjustment');
    }

    public function hasPendingAdjustment(): bool
    {
        if ($this->relationLoaded('adjustments')) {
            return $this->adjustments->where('status', 'pending')->isNotEmpty();
        }

        return $this->adjustments()->where('status', 'pending')->exists();
    }

    public function canBeAdjusted(): bool
    {
        return !in_array($this->status, ['exported', 'cancelled']);
    }

    /**
     * Tính tổng giá trị đơn hàng (VNĐ)
     */
    public function getTotalAmountAttribute(): int
    {
        return (int) $this->items->sum(function ($item) {
            return $item->quantity * $item->price;
        });
    }

    /**
     * Định dạng tổng tiền chuẩn tiền tệ Việt Nam
     */
    public function getFormattedTotalAmountAttribute(): string
    {
        return number_format($this->total_amount, 0, ',', '.') . ' ₫';
    }
}
