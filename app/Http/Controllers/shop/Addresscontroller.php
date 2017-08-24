<?php

namespace App\Http\Controllers\shop;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\address;
use Cart;
use App\bank;
use App\Http\Requests\AddressStore;
use App\Http\Requests\AddressUpdate;

class Addresscontroller extends Controller
{
  /**
  * Display a listing of the resource.
  *
  * @return Response
  */
 public function index()
 {
   if (Cart::count()== 0) {
     return redirect()->route('cart.index')
         ->with('fail','กรุณาเลือกวัตถุมงคลก่อน');
   }else {
   if (Auth::check()) {
          //$cartItems = Cart::content();
          $address = Address::where('user_id', Auth::user()->id) ->orderBy('created_at', 'desc')
               ->take(5)
               ->get();
               $cart = Cart::content();

               $bank = Bank::all();
        return view('Address.index', compact('address','cart','bank'));
    }
    else {
        return redirect('auth/login')->with('EE', 'กรุณาล็อคอิน หรือสมัครสมาชิกก่อนทำรายการต่อไป');
    }
}

 }

 /**
  * Show the form for creating a new resource.
  *
  * @return Response
  */
 public function create()
 {
       return view('Address.create');
 }

 /**
  * Store a newly created resource in storage.
  *
  * @return Response
  */
 public function store(AddressStore $request)
 {



   $address = new Address();
   $address->fullname = $request->input('fullname');
   $address->phone = $request->input('phone');
   $address->state = $request->input('state');
   $address->city = $request->input('city');
    $address->country = $request->input('country');
        $address->pincode = $request->input('pincode');

   if (Auth::user()->id ) {
            $address->user_id = Auth::user()->id;
        }

           $address->save();

             return redirect()->route('Address.index')->with('status', 'เพิ่มข้อมูลจัดส่งสำเร็จ!!');
 }

 /**
  * Display the specified resource.
  *
  * @param  int  $id
  * @return Response
  */
 public function show($id)
 {
     //
 }

 /**
  * Show the form for editing the specified resource.
  *
  * @param  int  $id
  * @return Response
  */
 public function edit($id)
 {
      $address = Address::find($id);
      return view('Address.edit', compact('address'));
 }

 /**
  * Update the specified resource in storage.
  *
  * @param  int  $id
  * @return Response
  */
 public function update(AddressUpdate $request, $id)
 {
       $address = Address::find($id);
         $address->update($request->all());

         return redirect()->route('Address.index')
             ->with('status','อัพเดทข้อมูลเรียบร้อยแล้ว');
 }

 /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return Response
  */
 public function destroy($id)
 {
     //
 }
}
