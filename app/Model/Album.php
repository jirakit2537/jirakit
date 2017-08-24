<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Album extends Model {

  protected $table = 'albums';

  protected $fillable = array('name','description','cover_image');

  public function images()
    {
        return $this->hasMany('App\Model\Images');
    }
}
