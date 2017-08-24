<?php

namespace App\Http\Controllers\shop;

use Illuminate\Http\Request;
use App\Order;
use App\product;
use Auth;
use App\Model\Object;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $order = Order::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->paginate(5);

      return view('order.index', compact('order'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $order = Order::find($id);
      $orderdetail = product::where('order_id', $id)->paginate(50);
      foreach ($orderdetail as $item) {

        $object = Object::where('id', $item->product_id)->get();
      }
      $qty = $orderdetail->sum('qty');
      $total = $orderdetail->sum('total');
      $title = $order->id;
      return view('order.show', compact('orderdetail','object','qty','total','order'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
