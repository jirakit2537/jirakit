<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Order;

class product extends Model
{
  protected $fillable = ['total', 'tax', 'products_id', 'orders_id','qty'];
protected $table = 'order_product';

public function Order()
{
return $this->belongsTo(Order::class);
}

}
