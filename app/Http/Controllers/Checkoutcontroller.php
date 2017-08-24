<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use Cart;
use auth;
use Carbon\Carbon;

class Checkoutcontroller extends Controller
{


  public function index() {


    $cart = Cart::content();

    // return view('cart.welcome', compact('cart'));

  }


  public function store(request $request)
  {



    $order = new order();
    $order->total = $request->input('total');
    $order->deliveryprice = $request->input('deliveryprice');
    $order->address_id = $request->input('address');
    $order->bank_id = $request->input('bank_id');

if  ($request->input('deliveryprice') == 50) {
      $order->delivery = 'ไปรษณีย์ ems';
    }else{
      $order->delivery = 'kerry express';
    }

    if (Auth::user()->id ) {
             $order->user_id = Auth::user()->id;
         }


    $order->save();

    $cartItems = Cart::content();
    foreach ($cartItems as $cartItem) {
        $order->orderFields()->attach($cartItem->id, ['qty' => $cartItem->qty, 'tax' => Cart::tax(), 'total' => $cartItem->qty * $cartItem->price, 'created_at' => Carbon::now()]);
    }
     Cart::destroy();
    $cart = Cart::content();
    $order = Order::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->paginate(5);

        return view('order.index', compact('order'));
  }


}
