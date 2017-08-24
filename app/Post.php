<?php

namespace App;
use App\User;
use App\Tag;
use App\Post;
use App\Category;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Date\Date;

class Post extends Model
{
    protected $fillable = ['title', 'slug', 'summary', 'content','active','tag','name','category_id','showc'];

    protected $dates = ['created_at','brithday'];



    public function User()
{
    return $this->belongsTo(User::class);
}

public function tags()
  {
      return $this->belongsToMany('App\Tag');
  }

public function Category()
  {
      return $this->belongsTo('App\Category');
  }

  // public function Category()
  //   {
  //       return $this->belongsToMany('App\Category');
  //   }


  public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    //  public function setPublishedAttribute($date)
    //    {
     //
     //
     //
    //        $this->attributes['created_at'] = Date::parse($date)->subDay();
     //
    //    }


    //https://aprendible.com/blog/video-como-traducir-fechas-al-espanol-en-laravel-53 ที่มาของ date
       public function getCreatedAtAttribute($created_at)
{
    return new Date($created_at);
}


//คำนวณอายุจาก วันเกิด
       public function getAgeAttribute()
   {
       return Carbon::parse($this->attributes['brithday'])->age;
   }

//   ตัวอย่างแปลงค่า age2
//    public function getAge2Attribute()
// {
//    return Carbon::parse($this->attributes['created_at'])->age;
// }







}
