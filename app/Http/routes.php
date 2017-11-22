<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/index', function () use ($app) {
	return generateRandomString(50);
});

$app->post('/auth/login', 'AuthController@login');
$app->post('/auth/fb-login', 'AuthController@facebookLogin');
$app->get('/auth/logout', 'AuthController@logout');
$app->get('/auth/check-session', 'AuthController@checkSession');
$app->post('/auth/sign-up', 'AuthController@signUp');
$app->get('/auth/confirm-user', 'AuthController@confirmUser');
$app->post('/auth/update-device-token', 'AuthController@updateDeviceToken');
$app->post('/auth/update-location', 'AuthController@updateLocation');

$app->get('/contact', 'ContactController@getContacts');
$app->post('/contact', 'ContactController@addContact');
$app->delete('/contact/{contactId}', 'ContactController@removeContact');

$app->get('/card', 'CardController@index');
$app->get('/card/{cardId}', 'CardController@show');
$app->get('/card/{cardId}/image', 'CardController@showImage');

$app->get('/user', 'UserController@index');
$app->post('/user', 'UserController@update');
$app->get('/user/{userId}', 'UserController@show');

$app->get('/user/{userId}/qr', 'CardController@showQr');
$app->get('/user/{userId}/logo', 'CardController@showLogo');

$app->get('/user/{userId}/contact', 'ContactController@index');

$app->get('/user/{userId}/follower', 'FollowerController@index');
$app->get('/user/{userId}/following', 'FollowingController@index');

$app->get('/follower', 'FollowerController@index');
$app->get('/following', 'FollowingController@index');

$app->get('/user/{userId}/review', 'ReviewController@index');
$app->post('/user/{userId}/review', 'ReviewController@create');

$app->get('/user/{userId}/review/{reviewId}', 'ReviewController@show');

$app->get('/profession', 'ProfessionController@index');
$app->get('/notification', 'NotificationController@index');
$app->get('/push-notification', 'NotificationController@notificationPolling');

//$app->put('/user/{id}', 'UserController@update');

$app->get('profile/{userId}', 'ProfileController@show');


$app->group(['namespace' => 'App\Http\Controllers\Admin',
			 'prefix'    => 'admin'], function() use($app) {
	$app->get('index', 'AdminController@index');
	$app->get('card',  'CardController@index');
	$app->get('card/{id}',  'CardController@show');
	$app->get('field', 'AdminController@getFields');
	$app->post('upload-card-image/{cardId}', 'AdminController@uploadCardImage');
});
