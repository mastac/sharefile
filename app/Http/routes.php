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

Route::auth();

Route::get('/', 'HomeController@home');

Route::get('home', 'HomeController@home');

Route::get('upload', 'FileEntriesController@upload');
Route::post('store', 'FileEntriesController@store');
Route::get('download', 'FileEntriesController@download');

Route::get('myfiles', 'FileEntriesController@myfiles');
Route::get('download/{shorturl}', 'FileEntriesController@downloadfile');
Route::get('delete/{id}', 'FileEntriesController@delete');

Route::get('{shorturl}', 'FileEntriesController@download');
