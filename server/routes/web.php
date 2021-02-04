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

Route::get('/', 'Admin\UserController@index')->middleware('auth');
Route::any('/index/index', 'Admin\UserController@index')->name('index')->middleware('auth');
Route::any('/logout', 'Admin\UserController@logout')->name('logout');
Route::any('/login', 'Admin\UserController@login')->name('login');

Route::any('/sys/user', 'Admin\SysController@userLists')->middleware('auth');
Route::any('/sys/user/change', 'Admin\SysController@userChange')->middleware('auth');
Route::any('/sys/user/delete', 'Admin\SysController@userDelete')->middleware('auth');
Route::any('/sys/user/add', 'Admin\SysController@userAdd')->middleware('auth');
Route::any('/sys/user/modify', 'Admin\SysController@userModify')->middleware('auth');

Route::any('/sys/menu', 'Admin\SysController@menuLists')->middleware('auth');
Route::any('/sys/menu/change', 'Admin\SysController@menuChange')->middleware('auth');
Route::any('/sys/menu/delete', 'Admin\SysController@menuDelete')->middleware('auth');
Route::any('/sys/menu/add', 'Admin\SysController@menuAdd')->middleware('auth');
Route::any('/sys/menu/modify', 'Admin\SysController@menuModify')->middleware('auth');

Route::any('/tool/ocrtool', 'Admin\ToolsController@ocrToolLists')->middleware('auth');
Route::any('/tool/ocrtool/delete', 'Admin\ToolsController@ocrToolDelete')->middleware('auth');
Route::any('/tool/ocrtool/add', 'Admin\ToolsController@ocrToolAdd')->middleware('auth');
Route::any('/tool/ocrtool/upload', 'Admin\ToolsController@upload')->middleware('auth');
Route::any('/tool/ocrtool/create', 'Admin\ToolsController@create')->middleware('auth');
Route::any('/tool/ocrtool/download', 'Admin\ToolsController@download')->middleware('auth');

Route::any('/cloud/cloud', 'Admin\CloudController@cloudLists')->middleware('auth');
Route::any('/cloud/cloud/delete', 'Admin\CloudController@cloudDelete')->middleware('auth');
Route::any('/cloud/cloud/create', 'Admin\CloudController@create')->middleware('auth');
Route::any('/cloud/cloud/upload', 'Admin\CloudController@upload')->middleware('auth');
Route::any('/cloud/cloud/download', 'Admin\CloudController@download')->middleware('auth');

Route::any('/cloud/cloud/test', 'Admin\CloudController@test');
Route::get('/mail/send', 'MailController@send')->name('mail');

Route::view('/error', 'error')->name('error');
