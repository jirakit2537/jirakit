<?php

namespace App\Model;
use App\Model\Cateobject;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Date\Date;

class Object extends Model
{
    protected $table = 'objects';
      protected $dates = ['created_at','public'];
      protected $fillable = ['title', 'slug', 'name', 'Cateobject_id','public','hidden','content'];

    public function Cateobjects()
      {
          return $this->belongsTo('App\Model\Cateobject','Cateobject_id');
      }

      public function subobjects()
        {
            return $this->hasMany('App\Model\Subobject','Object_id');
        }


      public function getCreatedAtAttribute($created_at)
{
   return new Date($created_at);
}

public function getpublicAtAttribute($public)
{
return new Date($public);
}
}
