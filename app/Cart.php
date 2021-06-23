<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table="carts";
    protected $fillable = ['user_id','product_id'];

    public function product_detail()
    {
        return $this->belongsTo('App\Product','product_id','id');
    }
    
}
