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

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');

Route::get('threads', 'ThreadsController@index');
Route::get('threads/create', 'ThreadsController@create');
Route::get('threads/{channel}/{thread}', 'ThreadsController@show');

Route::post('threads-locked/{thread}', 'LockThreadsController@store')->name('lock-thread')->middleware('admin');

Route::delete('threads/{channel}/{thread}', 'ThreadsController@destroy');
Route::post('threads', 'ThreadsController@store')->middleware('verified');
Route::get('threads/{channel}', 'ThreadsController@index');

Route::get('threads/{channel}/{thread}/replies', 'RepliesController@index');
Route::post('threads/{channel}/{thread}/replies', 'RepliesController@store');
Route::post('threads/{channel}/{thread}/subscriptions', 'SubscriptionController@store');
Route::delete('threads/{channel}/{thread}/subscriptions', 'SubscriptionController@destroy');
Route::patch('/replies/{reply}', 'RepliesController@update');
Route::delete('/replies/{reply}', 'RepliesController@destroy');
Route::post('/replies/{reply}/best', 'BestRepliesController@store');

Route::post('/replies/{reply}/favorites', 'FavoritesController@store');
Route::delete('/replies/{reply}/favorites', 'FavoritesController@destroy');

Route::get('profiles/{user}', 'ProfilesController@show')->name('profile');
Route::get('profiles/{user}/notifications', 'UserNotifications@index');
Route::delete('profiles/{user}/notification/{notification}', 'UserNotifications@destroy');


Route::post('api/users/{user}/avatar', 'Api\UserAvatarController@store')->name('avatar');