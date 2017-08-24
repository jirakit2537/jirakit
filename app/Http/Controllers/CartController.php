<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Object;
use Cart;


class CartController extends Controller
{
public function index() {


  $cart = Cart::content();

  return view('cart.welcome', compact('cart'));

}


  public function Addcart(Request $request, $id) {
    $products = Object::find($id); // get prodcut by id
  //  if(isset($request->newPrice))
  //  {
  //    $price = $request->newPrice; // if size select
  //  }
  //  else{
  //    $price = $products->pro_price; // default price
  //  }
   Cart::add($products->id, $products->title, 1, $products->price,['img' => $products->images,'slug' => $products->slug]);
   $request->session()->flash('status', 'เพิ่มวัตถุมงคลในตะกร้าสินค้าสำเร็จ');
    return back();
}


public function removecart($id) {
  Cart::remove($id);

       return back()->with('status', 'ลบข้อมูลเรียบร้อย'); // will keep same page
}
public function update(Request $request, $id)
   {
       $qty = $request->qty;
             $proId = $request->proId;
          $rowId = $request->rowId;
           Cart::update($rowId,$qty); // for update
           $cartItems = Cart::content(); // display all new data of cart
           $request->session()->flash('status', 'ตะกร้าสินค้า อัพเดทสำเร็จ');
           return view('cart.upCart', compact('cartItems'));
          //  return back();
           /*  $products = products::find($proId);
             $stock = $products->stock;
             if($qty<$stock){
                 $msg = 'Cart is updated';
                Cart::update($id,$request->qty);
                return back()->with('status',$msg);
             }else{
                  $msg = 'Please check your qty is more than product stock';
                   return back()->with('error',$msg);
             }        */
         }

}
