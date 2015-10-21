<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/',  ["uses"=>"HomeController@index"]);

Route::get('api/resources', ["uses"=>"Api\ResourceController@index"] );
Route::get('api/resources/children', ["uses"=>"Api\ResourceController@children"] );
