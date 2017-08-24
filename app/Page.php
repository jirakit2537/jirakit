<?php

namespace App;

use Baum\Node;
use App\Presenters\PagePresenter;
use McCool\LaravelAutoPresenter\HasPresenter;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;



class Page extends Node implements HasPresenter
{
     use SoftDeletes;
  protected $dates = ['deleted_at'];
  protected $fillable = ['title', 'name', 'uri', 'icon', 'content','template','hidden'];



    public function updateOrder($order, $orderPage)
    {
        $orderPage = $this->findOrFail($orderPage);

        if ($order == 'before') {
            $this->moveToLeftOf($orderPage);
        } elseif ($order == 'after') {
            $this->moveToRightOf($orderPage);
        } elseif ($order == 'childOf') {
            $this->makeChildOf($orderPage);
        }
    }
    public function setNameAttribute($value)
       {
           $this->attributes['name'] = $value ?: null;
       }

       public function setTemplateAttribute($value)
       {
           $this->attributes['template'] = $value ?: null;
       }

       public function setHiddenAttribute($value)
       {
           $this->attributes['hidden'] = $value ?: 0;
       }

    public function getPresenterClass()
        {
            return PagePresenter::class;
        }


}
