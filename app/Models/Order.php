<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'total_price', 'status',
        'shipping_name', 'shipping_postal_code',
        'shipping_prefecture', 'shipping_address', 'shipping_building',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusLabel(): string
    {
        return ['支払い待ち', '支払い済み', '発送済み', '受取完了', 'キャンセル'][$this->status] ?? '不明';
    }
}
