<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Date\Date;



class Helpabbot extends Model
{
  protected $table = 'helpabbots';
  protected $fillable = ['name', 'oldname', 'wataddress', 'wataddress', 'ntname', 'btname', 'wtname', 'lwtname', 'ptname', 'otname', 'created_at','updated_at','imgabbots'];


      public function getDates()
{
    return ['bodthday','both','created_at'];
}

public function getAgeAttribute()
{
    return Date::parse($this->attributes['bodthday'])->age;
}

public function getAgebothAttribute()
{
    return Date::parse($this->attributes['both'])->age;
}

public function getCreatedAtAttribute($created_at)
{
return new Date($created_at);
}


}
