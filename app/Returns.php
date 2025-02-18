<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Returns extends Model
{
  use SoftDeletes;
  public function product(){
    return $this->belongsTo('App\Product');
  }

}