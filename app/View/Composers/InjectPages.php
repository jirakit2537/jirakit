<?php

namespace App\View\Composers;

use App\Page;
use Illuminate\View\View;
use Cart;

class InjectPages
{
    protected $pages;

    public function __construct(Page $pages)
    {
        $this->pages = $pages;
    }

    public function compose(View $view)
    {
        $pages = $this->pages->where('hidden', false)->get()->toHierarchy();

        $view->with('page', $pages);
    }
}
