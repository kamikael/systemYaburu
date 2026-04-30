<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class shops extends Model
{

use HasFactory;
    //
    protected $fillable = [
        "user_id",
        "shop_name",
        "shop_slug",
        "description",
        "logo_url"
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
