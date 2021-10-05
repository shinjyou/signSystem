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

// Route::get('/', function () {
//     return view('view.test');
// });

Route::get('/', 'OrgController@login');
Route::get('login', 'OrgController@login');
Route::post('login', 'OrgController@collation');
Route::get('page', 'OrgController@page');
Route::get('room', 'OrgController@room');
Route::get('create', 'OrgController@add');
Route::get('reset', 'OrgController@reset');
Route::post('url', 'OrgController@url');
Route::post('resave', 'OrgController@resaveSet');
Route::get('admin', 'OrgController@admin');
Route::post('new', 'OrgController@newBuildingSet');
Route::post('delete', 'OrgController@buildingDelete');
Route::post('ajax', 'OrgController@ajax');
Route::post('partDelete', 'OrgController@partDelete');
Route::post('partAdd', 'OrgController@partAdd');

