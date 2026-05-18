<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserAccount extends Model
{
    use HasFactory;

    protected $table = 'user_accounts';

    protected $fillable = [
        'user_id',
        'account_id',
        'owner',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'account_id' => 'string',
        'owner' => 'boolean',
    ];

    /**
     * Relation vers User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation vers Account
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}