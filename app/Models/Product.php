<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'purchase_instructions',
        'store_id',

        'price',
        'price_promo',

        'quantity',
        'min_quantity',
        'max_quantity',

        'start_promo',
        'end_promo',

        'product_type_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'price_promo' => 'decimal:2',

        'quantity' => 'integer',
        'min_quantity' => 'integer',
        'max_quantity' => 'integer',

        'start_promo' => 'datetime',
        'end_promo' => 'datetime',
    ];

    /**
     * Relation avec le type de produit
     */
    public function productType()
    {
        return $this->belongsTo(ProductType::class);
    }

    /**
     * Vérifie si le produit est actuellement en promotion
     */
    public function isPromoActive(): bool
    {
        if (
            !$this->price_promo ||
            !$this->start_promo ||
            !$this->end_promo
        ) {
            return false;
        }

        $now = now();

        return $now->between(
            $this->start_promo,
            $this->end_promo
        );
    }

    /**
     * Retourne le prix actuel
     */
    public function getCurrentPriceAttribute(): float
    {
        if ($this->isPromoActive()) {
            return (float) $this->price_promo;
        }

        return (float) $this->price;
    }
}