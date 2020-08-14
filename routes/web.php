<?php

use Illuminate\Support\Facades\Route;

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



Auth::routes(['verify' => true, 'register' => false,'login' => false]);
Route::match(['get', 'post'], 'login', function(){
    return redirect('/');
});

Route::get('/', 'HomeController@redirectPlayStore')->name('redirectPlayStore');
Route::get('/home', 'HomeController@index')->name('home');



Route::get('/privacy-policy', function () {
    return view('privacy-policy');
});

Route::get('/meme/{id}', function () {
   return Redirect::to('https://play.google.com/store/apps/details?id=com.trichain.kenyasihami');
});


