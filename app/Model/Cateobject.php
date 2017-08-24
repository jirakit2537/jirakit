<?php

namespace App\Model;
use App\Model\Object;
use Illuminate\Database\Eloquent\Model;

class Cateobject extends Model
{
  protected $fillable = ['name'];

    public function objects()
  {
      return $this->hasMany('App\Model\Object');
  }
}
