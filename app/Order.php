<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\product;
use App\Bank;
use App\Address;
use App\User;
use Cart;

class Order extends Model
{
  protected $fillable = ['total', 'deliveryprice', 'bank_id', 'address_id'];
protected $table = 'orders';

public function orderFields() {
     return $this->belongsToMany(product::class)->withPivot('qty', 'total');
 }
 // public static function createOrder() {
 //     // for order inserting to database
 //
 //     $cartItems = Cart::content();
 //     foreach ($cartItems as $cartItem) {
 //         $order->orderFields()->attach($cartItem->id, ['qty' => $cartItem->qty, 'tax' => Cart::tax(), 'total' => $cartItem->qty * $cartItem->price , 'created_at' => Carbon::now()] );
 //     }
 // }


  public function User()
{
  return $this->belongsTo(User::class);
}

public function address()
{
return $this->belongsTo(Address::class);
}

public function bank()
{
return $this->belongsTo(Bank::class);
}

public function orderproduct()
   {
       return $this->hasMany('App\OrderItem');
   }
}
