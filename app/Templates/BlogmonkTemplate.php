<?php

namespace App\Templates;

use Carbon\Carbon;
use App\Post;
use App\Category;
use App\Comment;
use App\Page;
use Illuminate\View\View;

class BlogmonkTemplate extends AbstractTemplate
{
    protected $view = 'blogmonk';

    protected $posts;

    public function __construct(Post $posts)
    {
        $this->posts = $posts;
    }

    public function prepare(View $view, array $parameters)
    {
        $posts = $this->posts->where('active', '1')->Where('category_id', '9')->orderBy('id', 'DESC')->paginate(9);
        $cate = Category::all();
        $postres = $this->posts->where('active', '1')->orderBy('id', 'DESC')->take(6)->get();
        $view->with('posts', $posts)->with('cate', $cate)->with('postres', $postres);
    }
}
