<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{

  use SoftDeletes;
    public function sale(){
        return $this->hasMany('App\Sale');
    }

    public function sales(){
      return $this->hasMany('App\Sales');
  }

    public function customer(){
        return $this->belongsTo('App\Customer');
    }

    public function product(){
      return $this->belongsTo('App\Product');
  }

    public function stockintransit(){
      return $this->belongsTo('App\StockInTransit');
  }






}
