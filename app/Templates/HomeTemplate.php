<?php

namespace App\Templates;

use Carbon\Carbon;
use App\Post;
use App\Category;
use App\Comment;
use App\Model\Object;
use App\Model\Album;
use App\Model\Note;
use Illuminate\View\View;



class HomeTemplate extends AbstractTemplate
{
    protected $view = 'home';

    protected $posts;

    public function __construct(Post $posts)
    {
        $this->posts = $posts;
    }

    public function prepare(View $view, array $parameters)
    {

$objects = Object::where('open', '1')->inRandomOrder()->take(6)->get();
        $posts = $this->posts->where('active', '1')->orderBy('id', 'DESC')->get();
  $albums = Album::orderBy('id', 'DESC')->take(2)->get();
    $note = Note::orderBy('datein', 'asc')->take(5)->get();
        $view->with('posts', $posts)->with('objects', $objects)->with('albums', $albums)->with('note', $note);
    }
}
