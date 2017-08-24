<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Date\Date;

class Images extends Model {

  protected $table = 'images';

  protected $fillable = array('album_id','description','image');
  protected $dates = ['created_at'];
  public function albums()
{
  return $this->belongsTo('App\Model\Album');
}
public function getCreatedAtAttribute($created_at)
{
return new Date($created_at);
}
}
