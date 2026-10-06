<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderAdjustmentItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_adjustment_id',
        'order_item_id',
        'old_sku',
        'new_sku',
        'old_quantity',
        'new_quantity',
    ];

    protected function casts(): array
    {
        return [
            'old_quantity' => 'integer',
            'new_quantity' => 'integer',
        ];
    }

    public function adjustment(): BelongsTo
    {
        return $this->belongsTo(OrderAdjustment::class, 'order_adjustment_id');
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }

    public function newProductVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'new_sku', 'sku');
    }
}
