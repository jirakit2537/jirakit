<?php

namespace App\Templates;
use App\Helpabbot;
use Carbon\Carbon;
use App\Post;
use App\Category;
use App\Comment;
use App\Page;
use Illuminate\View\View;

class HelpTemplate extends AbstractTemplate
{
    protected $view = 'helpabbot';

    protected $posts;

    public function __construct(Post $posts)
    {
        $this->posts = $posts;
    }

    public function prepare(View $view, array $parameters)
    {

        $cate = Category::all();
        $help = Helpabbot::all();
        $postre = $this->posts->where('active', '1')->orderBy('id', 'ASC')->take(6)->get();
        $view->with('cate', $cate)->with('postre', $postre)->with('help', $help);
    }
}
