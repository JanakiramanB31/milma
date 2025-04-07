<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
  use SoftDeletes;
    public function category(){
       return $this->belongsTo('App\Category');
    }
    public function unit(){
        return $this->belongsTo('App\Unit');
    }
    public function tax(){
        return $this->belongsTo('App\Tax');
    }

    public function sale(){
        return $this->hasMany('App\Sale');
    }

    public function invoice(){
        return $this->belongsToMany('App\Invoice');
    }
}
