<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table="products";
    protected $fillable = ['name','category_id','description','price','make'];
    
    public function category_detail()
    {
        return $this->belongsTo('App\Category','category_id','id');
    }
}
