<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Post;
use App\Category;


class CategoryController extends Controller
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
   $category = Category::orderBy('id', 'DESC')->paginate(5);
     $cate = Category::all();

   return view('category.index', compact('Post','title','category','cate'));

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

   $category = new Category();
   $category->name = $request->input('name');
   $category->save();


   return redirect()->route('category.index')
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
    $category = Category::where('name', '=',$slug)->first();
         $cate = Category::all();
          $cate21 = $category->posts()->orderBy('id', 'DESC')->paginate(3);
    $title = $category->name;
      $postre = Post::where('active', '1')->orderBy('id', 'DESC')->take(6)->get();
    return view('category.show', compact('Post','title','category','cate','postre','cate21'));
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
   $category = Category::find($id);

   return view('category.edit', compact('category','title'));
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
      $user = Category::find($id);

      $this->validate($request, [

          'name' => 'required|unique:categories,name,'.$user->id,

      ]);
      Category::find($id)->update($request->all());

      return redirect()->route('category.index')
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
         $category = Category::find($id);
   //$category->posts()->detach();
   Category::destroy($id);
   return redirect()->route('category.index')
       ->with('success', 'ลบข้อมูลเรียบร้อย');
 }

}
