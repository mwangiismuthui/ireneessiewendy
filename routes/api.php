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


    Route::group(['middleware' => ['auth:api']], function () {
        Route::post('/updateprofle', 'UserAuthController@updateProfile');

        Route::get('/', 'PostController@index');

        Route::post('/post/upload', 'PostController@store');
        Route::get('/post/index', 'PostController@index');
        Route::get('/post/like/{id}', 'PostController@LikePost');
        Route::get('/post/share/{id}', 'ShareController@store');
        Route::get('/post/download/{id}', 'DownlodController@store');
        Route::get('/user/follow/{id}', 'PostController@follow');
        Route::get('/trending/posts', 'PostController@getTrending');
        Route::get('/followers/{user_id}', 'PostController@followers');
        Route::get('/followings/{user_id}', 'PostController@followings');
        Route::get('/profile/{user_id}', 'PostController@profile');
        Route::get('/refered-post/similar/posts/{post_id}', 'PostController@postFormRequestPostId');

        Route::get('/all/leaderBoards', 'LeaderBoardController@index');
        Route::post('/enroll/leaderBoard', 'LeaderBoardController@store');


        Route::get('/trending-users', 'PostController@trendingUsers');
        Route::get('/user-posts/{user_id}', 'PostController@userPosts');
        Route::get('/trending-tags', 'PostController@trendingHashtags');
        Route::get('/tags-posts/{tag}', 'PostController@hashtagPosts');
        Route::get('/search-posts/{query}', 'PostController@normalSearch');
    });
});
