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
Route::post('/test',function(){
    return "yo!";
});
Route::middleware('auth:api')->get('/auth/test',function(){
    $discussion =["title"=>"kawan's group",
    "url"=>"http://"."192.168.43.17".":8080/api/pictures/kawan.jpg"
];
$discussions=[$discussion,$discussion,$discussion,$discussion];
    return new Response($discussions,200,$headers=["Content-Type" =>"application/json"]);
});
Route::get('/test',function(){
    $discussion =["title"=>"kawan's group",
    "url"=>"http://"."192.168.43.17".":8080/api/pictures/kawan.jpg"
];
$discussions=[$discussion,$discussion,$discussion,$discussion];
    return new Response($discussions,200,$headers=["Content-Type" =>"application/json"]);
});
Route::get('/pictures/{id}',function(){
    return new Response('{"error" : "file not found"}',404);
});
