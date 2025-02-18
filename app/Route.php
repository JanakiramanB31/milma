<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Route extends Model
{
  use SoftDeletes;
  protected $fillable = [
    'name','route_number'
];
}