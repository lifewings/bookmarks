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

Route::get('/', function () {
    return view('welcome');
});

Route::get('bookmarks', 'BookmarkController@index')->name('bookmarks');
Route::get('bookmark/new', 'BookmarkController@bookmarkNew')->name('bookmark.new');
Route::get('bookmark/view/{id}', 'BookmarkController@bookmarkView')->name('bookmark.view');

Route::post('bookmark/create', 'BookmarkController@create')->name('bookmark.create');
Route::post('bookmark/export', 'BookmarkController@bookmarkExport')->name('bookmark.export');
Route::post('bookmark/delete', 'BookmarkController@bookmarkDelete')->name('bookmark.delete');
