<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'account_id',
        'name',
        'domain',
        'domain_alias',
    ];

    /**
     * Relation avec le compte
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Relation avec les produits
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Relation avec les catégories/types de produits
     */
    public function productTypes()
    {
        return $this->hasMany(ProductType::class);
    }
}