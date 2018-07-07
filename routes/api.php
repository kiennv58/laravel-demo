<?php
$exceptUpdateOnly = ['create', 'destroy', 'store', 'index'];

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('login', ['as' => 'login', 'uses' => 'LoginController@login']);

Route::group([
    'middleware' => 'auth:api',
], function () use ($exceptUpdateOnly) {
	Route::resource('customers', 'CustomerController');

	Route::get('assets/get-all-page', 'AssetController@getAllPage');
	Route::resource('assets', 'AssetController');

	Route::get('permissions/by-role', 'PermissionController@getByRole');
	Route::resource('permissions', 'PermissionController');

	Route::resource('roles', 'RoleController');

	Route::resource('products', 'ProductController');

	Route::get('orders/statistic-by-time', 'StatisticController@statisticByTime');
	Route::put('orders/update-status/{id}', 'OrderController@updateStatus');
	Route::get('orders/check-code', 'OrderController@checkCode');
	Route::resource('orders', 'OrderController');

	Route::resource('order-details', 'OrderDetailController');

	Route::resource('users', 'UserController');
	Route::put('users/active/{id}', 'UserController@active');
	Route::put('users/reset-password/{id}', 'UserController@resetPasswordDefault');

	Route::get('account', 'AccountController@index');
	Route::post('account/change-password', 'AccountController@changePassword');
	Route::post('account/update-profile', 'AccountController@updateProfile');
	Route::post('account/upload-avatar', 'AccountController@uploadAvatar');
	
	Route::get('helpers/{name}/{option?}', ['as' => 'helper.index', 'uses' => 'HelperController@index']);
});
