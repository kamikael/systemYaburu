<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

protected $fillable = [
    'shop_id',
    'customer_email',
    'customer_name',
    'total_amount',
    'status'
];

protected $casts = [
    'shop_id' => 'integer',
    'customer_email' => 'string',
    'customer_name' => 'string',
    'total_amount' => 'decimal:2',
    'status' => 'string'
];

   public function items()
{
    return $this->hasMany(Order_item::class);
} //
}
