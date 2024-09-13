<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
  protected $fillable = [
    'vehicle_number', 'make','model','vehicle_type_parent_id','fuel_type_parent_id','type_parent_id'
];
}