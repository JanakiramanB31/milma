<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model
{
  protected $casts = [
    'product_id' => 'integer',
    'price' => 'float'  
  ];
  public function product(){
    return $this->belongsTo('App\Product');
  }

  public function rate(){
    return $this->belongsTo('App\Rate');
  }
}
