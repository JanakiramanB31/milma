<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
  protected $fillable = [
    'price_type_parent_id','price_code_parent_id'
];
}