<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class shops extends Model
{

use HasFactory;
    //
    protected $fillable = [
        'shop_name',
        'shop_slug',
        'description',
        'logo_url',
        'user_id'
    ];
    
      // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products(){

        return $this->hasMany(Product::class);

    }

    public function categores(){

        return $this->hasMany(Category::class);

    }

    

}
