<?php
Route::resource('categories', 'CategoryController');

Route::resource('blogs', 'BlogController');
Route::get('blogs/{id}/active', 'BlogController@active');
Route::get('blogs/{id}/hot', 'BlogController@hot');
Route::post('blogs/upload', 'BlogController@upload')->name('blogs.upload');

Route::resource('products', 'ProductController');
// Route::resource('pages', 'PageController');
Route::get('orders/search', 'ProductController@search')->name('products.search');
Route::resource('orders', 'OrderController');
Route::get('customers/search', 'CustomerController@search')->name('customers.search');
Route::resource('customers', 'CustomerController');

Route::group(['middleware' => ['role:superadmin']], function() {
	Route::resource('permissions', 'PermissionController', ['except' => ['show', 'create']]);
	Route::resource('roles', 'RoleController', ['except' => ['show', 'create']]);
	Route::resource('accounts', 'UserController');
	Route::get('accounts/active/{id}', 'UserController@active')->name('accounts.active');
	Route::get('accounts/reset-password/{id}', 'UserController@resetPasswordDefault')->name('accounts.reset-password');
});

// Route::get('change-password', 'AdminController@getChangePassword')->name('get-change-password');
// Route::post('change-password', 'AdminController@changePassword')->name('change-password');