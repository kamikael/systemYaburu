<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderDetail extends Model
{
    use HasFactory;

    protected $table = 'order_details';

    protected $fillable = [
        'product_id',
        'unit_price',
        'quantity',
        'order_id',
    ];

    protected $casts = [
        'product_id' => 'integer',
        'order_id' => 'integer',
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
    ];

    /**
     * Relation vers la commande
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relation vers le produit
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Retourne le total de la ligne
     */
    public function getSubtotalAttribute(): float
    {
        return (float) (
            $this->unit_price * $this->quantity
        );
    }
}