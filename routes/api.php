<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::prefix('v1')->group(function () {
    Route::post('/newlogin', 'UserAuthController@userLogin');
    Route::post('/newregister', 'UserAuthController@registerUser');   
    Route::post('/anonymousregister', 'UserAuthController@anonymousRegister');   
    Route::post('/forgotpassword', 'UserAuthController@forgot_password');
    Route::post('/tokenconnfrm', 'UserAuthController@token_connfrm');
    Route::post('/changePassword', 'UserAuthController@changePassword');
  

    Route::group(['middleware' => ['auth:api']], function(){
        Route::post('/updateprofle', 'UserAuthController@updateProfile');

        Route::get('/', 'PostController@index');
     
        Route::post('/post/upload', 'PostController@store')->name('upload');
    });
});