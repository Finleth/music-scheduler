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

Route::get('/musicians', 'MusicianController@index')->name('musicians-list');
Route::get('/musicians/new', 'MusicianController@new')->name('musician-new');
Route::post('/musicians/new', 'MusicianController@create')->name('musician-create');
Route::get('/musicians/edit/{id}', 'MusicianController@edit')->name('musician-edit');
Route::post('/musicians/edit/{id}', 'MusicianController@update')->name('musician-update');
