<?php

namespace App\Http\Controllers;
use App\User;
use App\Role;
use App\Post;
use App\Page;
use App\Tag;
use App\Comment;
use Auth;
use App\Category;
use App\Model\Album;
use App\Model\Images;
use App\Model\Object;
use App\Model\Cateobject;
use App\Helpabbot;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'แผงควบคุม';
        $total = User::count();
        $totalrole = Role::count();
        $totalpage = Page::count();
        $totalpost = Post::where('active', '1')->count();
        $totalTag = Tag::count();
        $totalCategory = Category::count();
        $totalComment = Comment::count();
        $totalgallery = Album::count();
        $totalimg = Images::count();
        $totalobject = Object::count();
        $totalcateobject = Cateobject::count();
        $totalhelp = Helpabbot::count();

  $lastUser = User::latest()->first();
    $lastrole = Role::latest()->first();
     $lastpage = Page::latest()->first();
      $lastpost = Post::where('active', '1')->latest()->first();
       $lasttag = Tag::latest()->first();
        $lastcategory = Category::latest()->first();
         $lastComment = Comment::latest()->first();
        return view('home', compact('totalobject','totalcateobject','totalhelp','totalgallery','totalimg','total', 'totalrole','title','totalComment','totalCategory', 'totalTag', 'totalpost','totalpage','lastpage','lastUser','lastrole','lastpage','lastpost','lasttag','lastcategory','lastComment'));
    }

}
