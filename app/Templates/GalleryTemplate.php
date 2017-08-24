<?php

namespace App\Templates;

use Carbon\Carbon;
use App\Post;
use App\Category;
use App\Comment;
use App\Page;
use App\Model\Album;
use Illuminate\View\View;

class GalleryTemplate extends AbstractTemplate
{
    protected $view = 'album';

    protected $posts;

    public function __construct(Post $posts)
    {
        $this->posts = $posts;
    }

    public function prepare(View $view, array $parameters)
    {
      $albums = Album::orderBy('id', 'DESC')->paginate(6);

      $cate = Category::all();
      $postre = $this->posts->where('active', '1')->orderBy('id', 'DESC')->take(6)->get();
      $view->with('albums', $albums)->with('cate', $cate)->with('postre', $postre);
    }
}
