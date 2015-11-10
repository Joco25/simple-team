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
Route::get('/', 'HomeController@index');

// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Registration routes...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');

// Password reset link request routes...
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');

// Password reset routes...
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', 'AppController@index');
    Route::resource('/api/attachments', 'ApiAttachmentController');
    Route::resource('/api/teams', 'ApiTeamController');

    Route::post('/api/projects/order', 'ApiProjectController@updateOrder');
    Route::resource('/api/projects', 'ApiProjectController');

    Route::delete('/api/stages/{id}/cards', 'ApiStageController@deleteAllCards');
    Route::resource('/api/stages', 'ApiStageController');

    Route::post('/api/cards/tags', 'ApiCardController@updateTags');
    Route::put('/api/cards/{id}/updateStage', 'ApiCardController@updateStage');
    Route::post('/api/cards/users', 'ApiCardController@updateUsers');
    Route::post('/api/cards/withoutStage', 'ApiCardController@storeWithoutStage');
    Route::put('/api/cards/stageOrder', 'ApiCardController@updateStageOrder');
    Route::resource('/api/cards', 'ApiCardController');

    /**
     * Conversations / Topic Posts
     */
    Route::resource('api/posts', 'ApiTopicPostController');
	Route::post('api/posts/(:num)/like', 'ApiTopicPostController@like');
	Route::delete('api/posts/(:num)/like', 'ApiTopicPostController@like');

    /**
     * Conversations / Topics
     */
    Route::resource('api/topics', 'ApiTopicPostController');
	Route::get('api/topics/latest', 'ApiTopicPostController@latest');
	Route::get('api/topics/starred', 'ApiTopicPostController@starred');
	Route::get('api/topics/unread', 'ApiTopicPostController@unread');
	Route::get('api/topics/top', 'ApiTopicPostController@top');

	Route::post('api/topics/(:num)/star', 'ApiTopicPostController@star');
	Route::delete('api/topics/(:num)/star', 'ApiTopicPostController@star');
	Route::post('api/topics/(:num)/view', 'ApiTopicPostController@view');
	Route::get('api/topics/(:num)/users/(:num)/notification', 'ApiTopicPostController@user_notification');
	Route::post('api/topics/(:num)/users/(:num)/notification', 'ApiTopicPostController@user_notification');
	Route::delete('api/topics/(:num)/users/(:num)/notification', 'ApiTopicPostController@user_notification');

    /**
     * Subtasks
     */
    Route::resource('/api/subtasks', 'ApiSubtaskController');

    /**
     * Comments
     */
    Route::resource('/api/comments', 'ApiCommentController');

    /**
     * Tags
     */
    Route::resource('/api/tags', 'ApiTagController');

    /**
     * Teams
     */
    Route::delete('/api/teams/user/{id}', 'ApiTeamController@deleteUser');
    Route::post('/api/teams/user', 'ApiTeamController@createUser');
    Route::resource('/api/teams', 'ApiTeamController');

    Route::put('/api/me/team', 'ApiUserController@updateTeam');
    Route::put('/api/me', 'ApiUserController@updateMe');
    Route::put('/api/me/password', 'ApiUserController@updatePassword');
    Route::resource('/api/users', 'ApiUserController');
});
