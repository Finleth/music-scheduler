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

// Musician blackout routes
Route::get('/musicians/{id}/blackout/new', 'MusicianBlackoutController@new')->name('blackout-new');
Route::post('/musicians/{id}/blackout/new', 'MusicianBlackoutController@create')->name('blackout-create');
Route::get('/musicians/{musician}/blackout/{blackout}/edit', 'MusicianBlackoutController@edit')->name('blackout-edit');
Route::post('/musicians/{musician}/blackout/{blackout}/edit', 'MusicianBlackoutController@update')->name('blackout-update');
Route::get('/musicians/{musician}/blackout/{blackout}/delete', 'MusicianBlackoutController@delete')->name('blackout-delete');

// Schedule event type routes
Route::get('/schedule-event-types', 'ScheduleEventTypeController@index')->name('schedule-event-types-list');
Route::get('/schedule-event-types/new', 'ScheduleEventTypeController@new')->name('schedule-event-type-new');
Route::post('/schedule-event-types/new', 'ScheduleEventTypeController@create')->name('schedule-event-type-create');
Route::get('/schedule-event-types/{id}/edit', 'ScheduleEventTypeController@edit')->name('schedule-event-type-edit');
Route::post('/schedule-event-types/{id}/edit', 'ScheduleEventTypeController@update')->name('schedule-event-type-update');
Route::get('/schedule-event-types/{id}/delete', 'ScheduleEventTypeController@delete')->name('schedule-event-type-delete');

// Musician schedule event type routes
Route::get('/musicians/{id}/event/new', 'MusicianScheduleEventTypeController@new')->name('musician-event-new');
Route::post('/musicians/{id}/event/new', 'MusicianScheduleEventTypeController@create')->name('musician-event-create');
Route::get('/musicians/{musician}/event/{event}/edit', 'MusicianScheduleEventTypeController@edit')->name('musician-event-edit');
Route::post('/musicians/{musician}/event/{event}/edit', 'MusicianScheduleEventTypeController@update')->name('musician-event-update');
Route::get('/musicians/{musician}/event/{event}/delete', 'MusicianScheduleEventTypeController@delete')->name('musician-event-delete');

// Schedule routes
Route::get('/schedule', 'ScheduleController@index')->name('schedule-list');

// Schedule event routes
Route::get('/schedule-event/{id}/edit', 'ScheduleEventController@edit')->name('schedule-event-edit');
Route::post('/schedule-event/{id}/edit', 'ScheduleEventController@update')->name('schedule-event-update');
