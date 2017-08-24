<?php

namespace App\Presenters;


use Carbon\Carbon;
use McCool\LaravelAutoPresenter\BasePresenter;
use League\CommonMark\CommonMarkConverter;

class PagePresenter extends BasePresenter
{
  protected $markdown;

  public function contentpage()
     {
       $markdown = new CommonMarkConverter();
         return $this->markdown->convertToHtml($this->content);
     }

    public function uricard()
    {
        return $this->uri.'*';
    }

    public function prettyUri()
    {
        return '/'.ltrim($this->uri, '/');
    }

    public function linkToPaddedTitle($link)
    {
        $padding = str_repeat('<i class="fa fa-arrow-right" aria-hidden="true">&nbsp;</i>', $this->depth * 2);

        return $padding.link_to($link, $this->title);
    }

    public function pedtitle()
    {
        return str_repeat("=> ", $this->depth * 1).$this->title;
    }

}
