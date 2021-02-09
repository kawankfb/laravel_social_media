<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', 'App\Http\Controllers\AuthController@login');
    Route::post('logout', 'App\Http\Controllers\AuthController@logout');
    Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');
    Route::post('me', 'App\Http\Controllers\AuthController@me');

});
Route::post('auth/register','App\Http\Controllers\AuthController@register');
Route::get('/pictures/{id}',function(){
    return new Response('{"error" : "file not found"}',404);
});
Route::middleware('api')->apiResource('discussion','App\Http\Controllers\DiscussionController');

Route::middleware('api')->apiResource('followed_discussions','App\Http\Controllers\FollowedDiscussionController');
Route::middleware('api')->delete('followed_discussions','App\Http\Controllers\FollowedDiscussionController@destroy');

Route::middleware('api')->apiResource('files','App\Http\Controllers\FilesController');
Route::get('profile_picture/{id}','App\Http\Controllers\FilesController@profile_picture');
Route::middleware('api')->delete('files','App\Http\Controllers\FilesController@destroy');


Route::middleware('api')->apiResource('posts','App\Http\Controllers\PostController');
Route::middleware('api')->delete('posts','App\Http\Controllers\PostController@destroy');
Route::middleware('api')->put('posts','App\Http\Controllers\PostController@update');
Route::middleware('api')->get('personal_discussions','App\Http\Controllers\DiscussionController@showPersonalDiscussions');

