<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function customer(){
      return $this->belongsTo(Customer::class, 'customer_id');
    }
}