<?php

namespace App;


use Zizaco\Entrust\Traits\EntrustUserTrait;
use App\Post;
use App\Order;
use App\Address;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\MailResetPasswordToken;


class User extends Authenticatable
{
	use Notifiable;
    use EntrustUserTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function Post()
 {
     return $this->hasMany(Post::class);
 }
 public function Order()
{
	return $this->hasMany(Order::class);
}

 public function Address()
{
	return $this->hasMany(Address::class);
}

 public function sendPasswordResetNotification($token)
{
    $this->notify(new MailResetPasswordToken($token));
}
}
