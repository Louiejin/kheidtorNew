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

Route::get('/me', 'AuthController@me');
Route::get('/', function () {
    return redirect()->route('home');
});
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/login', 'AuthController@showform')->name('login');
Route::post('/login', 'AuthController@login');
Route::get('/logout', 'AuthController@logout');

// users
Route::get('/users', 'UserController@readAll')->name('users');
Route::get('/user/new', 'UserController@create');
Route::post('/user', 'UserController@store');
//Route::get('/user/{id}', 'UserController@get');
//Route::get('/user/me/edit', 'UserController@edit');
Route::get('/user/{id}/edit', 'UserController@edit');
Route::patch('/user/{user}', 'UserController@update');
Route::patch('/user/{user}/updatepassword', 'UserController@updatepassword');
Route::post('/user/{user}/deactivate', 'UserController@deactivate');
Route::post('/user/{user}/activate', 'UserController@activate');
Route::post('/user/{user}/updatewordpress', 'UserController@updateWordpress');
Route::post('/user/{user}/unlinkwordpress', 'UserController@unlinkWordpress');

// articles
Route::get('/article/{article}/convert', 'ArticleController@convert');
Route::get('/article/{article}/convert/basic', 'ArticleController@basic');
Route::get('/article/{article}/convert/advanced', 'ArticleController@advanced');
Route::get('/article/{article}/convert/manual', 'ArticleController@manual');

Route::post('/article/{article}/convert/basic', 'ArticleController@basic');
Route::post('/article/{article}/convert/advanced', 'ArticleController@advanced');
Route::patch('/article/{article}/convert/manual', 'ArticleController@manual');
Route::patch('/article/{article}', 'ArticleController@update');

Route::get('/articles', 'ArticleController@readAll')->name('articles');
Route::get('/article/new', 'ArticleController@create');
Route::post('/article', 'ArticleController@store');
Route::get('/article/{article}/preview', 'ArticleController@get');
Route::get('/article/{article}/edit', 'ArticleController@edit');

Route::delete('/article/{id}', 'ArticleController@destroy');
Route::post('/article/{article}/publish', 'ArticleController@publish');

// databases
Route::get('/databases', 'KhengineController@get')->name('databases');
Route::post('/database', 'KhengineController@store');
Route::get('/databases/history', 'KhengineController@history');
Route::get('/database/{update}', 'KhengineController@download');

Route::get('/unauthorized', 'AuthController@unauthorized')->name('unauthorized');
