<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order_item extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price'
    ];

    protected $casts = [
        'order_id' => 'integer',
        'product_id' => 'integer',
        'quantity' => 'integer',
        'unit_price' => 'decimal:2'
    ];

    // 🔹 Relation vers commande
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // 🔹 Relation vers produit
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}