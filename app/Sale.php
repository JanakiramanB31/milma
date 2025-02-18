<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
  use SoftDeletes;
    public function invoice(){
        return $this->belongsTo('App\Invoice');
    }

    public function product(){
        return $this->belongsTo('App\Product');
    }
    public function customer(){
      return $this->belongsTo('App\Customer');
    }

}
