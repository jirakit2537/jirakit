<?php

namespace App\Http\Controllers;


use App\Post;
use App\Tag;
use App\Comment;
use Auth;
use App\Category;
use Image;
use Storage;
use File;
use Counter;

use Illuminate\Http\Request;

class BlogFrontendController extends Controller
{
  public function show($slug)
  {
    $tags = Tag::all();
    $category = Category::all();
    $comments = Comment::all();

$postre = Post::where('active', '1')->orderBy('id', 'DESC')->take(6)->get();
   $cate = Category::all();
   //   $commentCT = Comment::where('approved', true)->count();
     //  $commentCF = Comment::where('approved', false)->count();
    $post = Post::where('slug', '=',$slug)->first();

    $title = $post->title;
     Counter::showAndCount('Post.show', $post->id);
    return view('Post.show', compact('post','title','tags','category','comments','postre','cate'));
  }


  public function searchpost(Request $request)
  {
    $search = $request->input('search');
         $cate21 = Post::whereHas('User', function($q) use ($search)
{
    $q->where('name','like','%'."$search".'%');
})->orwhereHas('Category', function($q) use ($search)
{
$q->where('name','like','%'."$search".'%');
})->
orwhere('title','like','%'."$search".'%')->orwhere('content','like','%'."$search".'%')->orderBy('id')->paginate(3);


    $links = $cate21->appends(request()->except('page')) ->links();
    $total = $cate21->total();
   $cate = Category::all();
$title = "ค้นหาข้อมูลโพส";
$postre = Post::where('active', '1')->orderBy('id', 'DESC')->take(6)->get();
 return view('post.sreach', compact('Posts','title','links','cate','postre','cate21','search','total'));

}
}
