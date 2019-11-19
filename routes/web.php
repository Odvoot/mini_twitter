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
    return redirect()->route('home');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::post('/tweet', 'TweetController@tweet')->name('post.tweet');
Route::post('/comment', 'CommentController@comment')->name('post.comment');
Route::get('/profile', 'ProfileController@profile')->name('profile');
Route::post('/photo_upload', 'ProfileController@upload_photo')->name('profile.update_photo');
