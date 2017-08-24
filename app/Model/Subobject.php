<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Subobject extends Model
{
    protected $table = 'subobjects';
    protected $fillable = ['namephoto', 'Object_id', 'imgfront', 'imgback', 'imgleft', 'imgright'];


    public function objects()
  {
    return $this->belongsTo('App\Model\Object');
  }
}
