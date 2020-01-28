<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/auth/redirect/{provider}', 'SocialController@redirect');
Route::get('/twitter.php', 'SocialController@callback');

Route::post("/tweet", "VideoController@uploadThenTweet");
Route::post("/download", "VideoController@uploadThenDownload");