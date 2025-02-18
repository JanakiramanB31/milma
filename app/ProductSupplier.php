<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductSupplier extends Model
{

  use SoftDeletes;
    public function product(){
        return $this->belongsTo('App\Product');
    }

    public function supplier(){
        return $this->belongsTo('App\Supplier');
    }


}
