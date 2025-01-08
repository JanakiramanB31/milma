<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{

    public function sale(){
        return $this->belongsTo('App\Sale');
    }

    public function sales(){
      return $this->belongsTo('App\Sales');
    }

    public function product(){
      return $this->belongsTo('App\Product');
    }

    public function invoice(){
        return $this->belongsTo('App\Invoice');
    }

    public function customer(){
      return $this->belongsTo('App\Customer');
  }
  public function stockintransit(){
    return $this->belongsTo('App\StockInTransit');
}


}