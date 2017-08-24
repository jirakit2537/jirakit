<?php

namespace App\Templates;

use Carbon\Carbon;
use App\Post;
use App\Category;
use App\Comment;
use App\Page;
use App\Model\Object;
use App\Model\Cateobject;
use Illuminate\View\View;

class ObjectTemplate extends AbstractTemplate
{
    protected $view = 'object';

    protected $posts;

    public function __construct(Post $posts)
    {
        $this->posts = $posts;
    }

    public function prepare(View $view, array $parameters)
    {
        $objects = Object::where('open', '1')->orderBy('id', 'DESC')->paginate(6);
        $cateob = Cateobject::all();
        $cate = Category::all();
        $postre = $this->posts->where('active', '1')->orderBy('id', 'DESC')->take(6)->get();
        $view->with('objects', $objects)->with('cate', $cate)->with('postre', $postre)->with('cateob', $cateob);
    }
}
