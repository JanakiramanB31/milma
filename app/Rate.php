<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
  public function productPrice(){
    return $this->hasMany('App\ProductPrice');
    
  }
  
}
