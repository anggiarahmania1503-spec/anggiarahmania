<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'payment_status',
        'shipping_name',
        'shipping_address',
        'shipping_phone',
        'total_amount',
        'shipping_cost',
        'payment_method',
        'notes',
    ];

    /**
     * CASTS
     * ⚠️ total_amount & shipping_cost JANGAN decimal
     */
    protected $casts = [
        'total_amount'  => 'integer',
        'shipping_cost' => 'integer',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
