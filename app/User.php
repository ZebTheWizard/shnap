<?php

namespace App;

use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    private $twitter_data = null;
    private $twitter_conn = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'twitter_id', 'oauth_token', 'oauth_token_secret'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function twitter() {
        if (!$this->twitter_data) {
            $conn = new TwitterOAuth(
                config('services.twitter.client_id'),
                config('services.twitter.client_secret'),
                $this->oauth_token,
                $this->oauth_token_secret
            );
    
            $twitter_data = $conn->get("users/show", [
                "user_id" => $this->twitter_id
            ]);
        }
        return $twitter_data;
    }
}
