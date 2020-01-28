<?php
 
namespace App\Http\Controllers;

use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Http\Request;
use Validator,Redirect,Response,File;
use App\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function redirect($provider)
    {
        $conn = new TwitterOAuth(
            config('services.twitter.client_id'),
            config('services.twitter.client_secret')
        );

        $res = $conn->oauth('oauth/request_token', [
            'oauth_callback' => config('services.twitter.redirect')
        ]);

        if ($conn->getLastHttpCode() != 200) {
            abort(403, "Twitter not authorized");
        }

        session([
            'oauth_token' => $res['oauth_token'],
            'oauth_secret'=> $res['oauth_token_secret']
        ]);

        $url = $conn->url('oauth/authorize', [
            'oauth_token' => $res['oauth_token']
        ]);

        return redirect($url);
    }
 
    public function callback(Request $r)
    {
        // dd(session('oauth_token'));
        $conn = new TwitterOAuth(
            config('services.twitter.client_id'),
            config('services.twitter.client_secret'),
            session('oauth_token'),
            session('oauth_secret')
        );

        $token = $conn->oauth('oauth/access_token', [
            'oauth_verifier' => filter_input(INPUT_GET, 'oauth_verifier')
        ]);

        // dump(session('oauth_token'));
        // dump(session('oauth_secret'));

        // dump($token);

        

        $user = $this->createUser($token, 'twitter');
 
        // dd($user);
        Auth::login($user, true);
 
        return redirect()->to('/home');
 
    }
   function createUser($getInfo,$provider){
        $user = User::firstOrNew([
            'twitter_id' =>  $getInfo['user_id']
        ]);

        $user->fill([
            "name" => $getInfo['screen_name'],
            "oauth_token" => $getInfo['oauth_token'],
            "oauth_token_secret" => $getInfo['oauth_token_secret'],
        ])->save();

        return $user;
   }
}