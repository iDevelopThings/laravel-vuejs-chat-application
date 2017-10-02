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
	return redirect()->route('home');
});

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
	Route::get('/home', 'HomeController@index')
		->name('home');

	Route::get('/conversations', 'ConversationController@conversations')
		->name('conversation.view-all');

	Route::post('/conversations/{user}', 'ConversationController@createConversation')
		->name('conversation.create-conversation');

	Route::get('/conversations/{conversation}', 'ConversationController@conversation')
		->name('conversation.view');

	Route::get('/conversations/{conversation}/messages', 'ConversationController@messages')
		->name('conversation.messages');

	Route::post('/conversations/{conversation}/messages', 'ConversationController@sendMessage');

	Route::get('/users', 'UserController@users')->name('users');
});
