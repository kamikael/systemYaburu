<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'amount_total',
        'order_number',

        'transaction_id',
        'transaction_status',
        'payment_method',

        'client_firstname',
        'client_lastname',
        'client_email',
        'client_phone',
        'client_contact',

        'client_country',
        'client_district',
        'client_city',
        'client_address',

        'store_id',
    ];

    protected $casts = [
        'amount_total' => 'decimal:2',

        'store_id' => 'integer',

        'client_email' => 'string',
        'client_phone' => 'string',
        'client_contact' => 'string',

        'transaction_id' => 'string',
        'transaction_status' => 'string',
        'payment_method' => 'string',
    ];

    protected $appends = [
        'client_fullname',
    ];

    /**
     * Relation avec le store
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Relation avec les détails de commande
     */
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    /**
     * Alias items()
     * pratique pour compatibilité ancienne logique
     */
    public function items()
    {
        return $this->hasMany(OrderDetail::class);
    }

    /**
     * Nom complet client
     */
    public function getClientFullnameAttribute(): string
    {
        return trim(
            ($this->client_firstname ?? '') .
            ' ' .
            ($this->client_lastname ?? '')
        );
    }

    /**
     * Vérifie si commande payée
     */
    public function isPaid(): bool
    {
        return $this->transaction_status === 'paid';
    }

    /**
     * Vérifie si commande en attente
     */
    public function isPending(): bool
    {
        return $this->transaction_status === 'pending';
    }

    /**
     * Vérifie si commande annulée
     */
    public function isCanceled(): bool
    {
        return $this->transaction_status === 'canceled';
    }
}