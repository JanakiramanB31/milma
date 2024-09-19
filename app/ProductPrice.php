<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model
{

    public function product(){
        return $this->belongsTo('App\Product');
    }

    public function rate(){
        return $this->belongsTo('App\Rate');
    }


}
