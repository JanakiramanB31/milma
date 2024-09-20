<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockInTransit extends Model
{
  public function product(){
    return $this->belongsTo('App\Product');
}

  public function route(){
      return $this->belongsTo('App\Route');
  }
  public function vehicle(){
    return $this->belongsTo('App\Vehicle');
}
}
