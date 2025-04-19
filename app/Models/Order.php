<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model {
    protected $fillable = [
        'order_uuid',
        'buyer_id',
        'total_price',
        'currency',
        'order_status',
        'payment_status',
        'payment_link',
        'link_expires_at'
    ];

    protected $table = 'orders';

    protected $casts = [
        'order_status' => OrderStatus::class,
        'payment_status' => PaymentStatus::class,
        'total_price' => 'decimal:2'
    ];

    public function getBuyer(): BelongsTo {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function getOrderMetas(): HasMany {
        return $this->hasMany(OrderMeta::class, 'order_id', 'id');
    }


}
