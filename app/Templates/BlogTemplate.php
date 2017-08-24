<?php

namespace App\Templates;

use Carbon\Carbon;
use App\Post;
use App\Category;
use App\Comment;
use App\Page;
use Illuminate\View\View;

class BlogTemplate extends AbstractTemplate
{
    protected $view = 'blog';

    protected $posts;

    public function __construct(Post $posts)
    {
        $this->posts = $posts;
    }

    public function prepare(View $view, array $parameters)
    {
        $posts = $this->posts->where('active', '1')->Where('category_id', '8')->orderBy('id', 'DESC')->paginate(9);
        $cate = Category::all();
        $postre = $this->posts->where('active', '1')->orderBy('id', 'DESC')->take(6)->get();
        $view->with('posts', $posts)->with('cate', $cate)->with('postre', $postre);
    }
}
