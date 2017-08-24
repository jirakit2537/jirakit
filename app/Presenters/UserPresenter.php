<?php

namespace App\Presenters;

use McCool\LaravelAutoPresenter\BasePresenter;

class UserPresenter extends BasePresenter
{
    public function lastLoginDifference()
    {
        return $this->last_login_at->diffForHumans();
    }
}
