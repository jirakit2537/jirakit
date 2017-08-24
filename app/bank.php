<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Order;

class bank extends Model
{
  protected $fillable = ['bankname', 'bankaddress', 'banknumber', 'bankusername'];
  protected $table = 'bank';

  public function Order()
  {
  return $this->belongsTo(Order::class);
  }
}
