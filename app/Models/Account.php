<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
{
    use HasFactory;

    protected $table = 'accounts';

    protected $fillable = [
        'uuid',
        'account_name',
        'account_ref',
        'account_feda_id',
    ];

    protected $casts = [
        'uuid' => 'string',
        'account_name' => 'string',
        'account_ref' => 'string',
        'account_feda_id' => 'string',
    ];

    /**
     * Relation avec les stores
     */
    public function stores()
    {
        return $this->hasMany(Store::class);
    }
}