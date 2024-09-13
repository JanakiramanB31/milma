<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
  protected $fillable = [
    'company_name', 'address_1','address_2','city','post_code'
];
}