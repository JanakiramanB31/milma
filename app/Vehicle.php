<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
  use SoftDeletes;
  protected $fillable = [
    'vehicle_number', 'make','model','vehicle_type_parent_id','fuel_type_parent_id','type_parent_id'
];
}