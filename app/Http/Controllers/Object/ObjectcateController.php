<?php

namespace App\Http\Controllers\Object;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Object;
use App\Post;
use App\Model\Cateobject;

class ObjectcateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index()
     {

     $Post = Post::all();
    $title = 'จัดการข้อมูล Tag';
    $category = Cateobject::orderBy('id', 'DESC')->paginate(5);


    return view('cateobject.index', compact('Post','title','category'));

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
         $this->validate($request, [

        'name' => 'required|unique:categories,name' ,


    ]);

    $category = new Cateobject();
    $category->name = $request->input('name');
    $category->save();


    return redirect()->route('cateobject.index')
        ->with('success','บันทึกข้อมูลเรียบร้อย');
     }

     /**
      * Display the specified resource.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function show($slug)
     {
           $Post = Post::all();
     $category = Cateobject::where('name', '=',$slug)->first();
          $cate = Cateobject::all();
           $cate21 = $category->objects()->paginate(4);
     $title = $category->name;
       $postre = Post::where('active', '1')->orderBy('id', 'DESC')->take(6)->get();
     return view('cateobject.show', compact('Post','title','category','cate','postre','cate21'));
     }

     /**
      * Show the form for editing the specified resource.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function edit($id)
     {
          $title = 'จัดการข้อมูลผู้ใช้';
    $category = Cateobject::find($id);

    return view('cateobject.edit', compact('category','title'));
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
       $user = Cateobject::find($id);

       $this->validate($request, [

           'name' => 'required|unique:categories,name,'.$user->id,

       ]);
       Cateobject::find($id)->update($request->all());

       return redirect()->route('cateobject.index')
           ->with('success','อัพเดทข้อมูลเรียบร้อย');
     }

     /**
      * Remove the specified resource from storage.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function destroy($id)
     {
          $category = Cateobject::find($id);
    //$category->posts()->detach();
    Cateobject::destroy($id);
    return redirect()->route('cateobject.index')
        ->with('success', 'ลบข้อมูลเรียบร้อย');
  }

 }
