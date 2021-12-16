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

// Musician routes
Route::get('/musicians', 'MusicianController@index')->name('musicians-list');
Route::get('/musicians/new', 'MusicianController@new')->name('musician-new');
Route::post('/musicians/new', 'MusicianController@create')->name('musician-create');
Route::get('/musicians/{id}/edit', 'MusicianController@edit')->name('musician-edit');
Route::post('/musicians/{id}/edit', 'MusicianController@update')->name('musician-update');

// Musician instrument routes
Route::get('/musicians/{id}/instrument/new', 'MusicianInstrumentController@new')->name('instrument-new');
Route::post('/musicians/{id}/instrument/new', 'MusicianInstrumentController@create')->name('instrument-create');
Route::get('/musicians/{musician}/instrument/{instrument}/edit', 'MusicianInstrumentController@edit')->name('instrument-edit');
Route::post('/musicians/{musician}/instrument/{instrument}/edit', 'MusicianInstrumentController@update')->name('instrument-update');
Route::get('/musicians/{musician}/instrument/{instrument}/delete', 'MusicianInstrumentController@delete')->name('instrument-delete');
