<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Order;

class Address extends Model
{
protected $fillable = ['fullname', 'phone', 'state', 'city', 'country', 'pincode'];
protected $table = 'address';
  public function User()
{
  return $this->belongsTo(User::class);
}

public function Order()
{
return $this->belongsTo(Order::class);
}
}
