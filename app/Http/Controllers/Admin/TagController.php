<?php

namespace App\Http\Controllers\Admin;
use App\Tag;
use App\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TagController extends Controller
{
  /**
  * Display a listing of the resource.
  *
  * @return Response
  */
 public function index()
 {
   $Post = Post::all();
   $title = 'จัดการข้อมูล Tag';
   $Tags = Tag::orderBy('id', 'DESC')->paginate(5);

   return view('tag.index', compact('Tags','title','Post'));
 }

 /**
  * Show the form for creating a new resource.
  *
  * @return Response
  */
 // public function create()
 // {
 //   $title = 'จัดการข้อมูลผู้ใช้';
 //   return view('tag.create', compact('title'));
 // }

 /**
  * Store a newly created resource in storage.
  *
  * @return Response
  */
 public function store(Request $request)
 {
   $this->validate($request, [

       'tag' => 'required|unique:tags,tag' ,


   ]);

   $tags = new Tag();
   $tags->tag = $request->input('tag');
   $tags->save();


   return redirect()->route('tag.index')
       ->with('success','บันทึกข้อมูลเรียบร้อย');
 }

 /**
  * Display the specified resource.
  *
  * @param  int  $id
  * @return Response
  */
  public function show($tag)
  {
    $Post = Post::all();
    $tags = Tag::where('tag', '=',$tag)->first();
    $title = $tags->tag;
    return view('tag.show', compact('Post','title','tags'));
  }

 /**
  * Show the form for editing the specified resource.
  *
  * @param  int  $id
  * @return Response
  */
 public function edit($id)
 {
   $title = 'จัดการข้อมูลผู้ใช้';
   $Tags = Tag::find($id);

   return view('tag.edit', compact('Tags','title'));
 }

 /**
  * Update the specified resource in storage.
  *
  * @param  int  $id
  * @return Response
  */
 public function update(Request $request, $id)
 {
   $user = tag::find($id);

   $this->validate($request, [

       'tag' => 'required|unique:tags,tag,'.$user->id,

   ]);
   tag::find($id)->update($request->all());

   return redirect()->route('tag.index')
       ->with('success','อัพเดทข้อมูลเรียบร้อย');
 }

 /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return Response
  */
 public function destroy($id)
 {
   $tag = Tag::find($id);
   $tag->posts()->detach();
   Tag::destroy($id);
   return redirect()->route('tag.index')
       ->with('success', 'ลบข้อมูลเรียบร้อย');
 }
}
