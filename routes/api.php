<?php

use Illuminate\Http\Request;

Route::group([
    // Prefixed with /auth
    'prefix' => 'auth',
], function() {
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::get('register/activate/{token}', 'AuthController@activate');

    // Requires Authorization
    Route::group([
        'middleware' => 'jwt.auth'
    ], function() {
        Route::get('logout', 'AuthController@logout');
        Route::get('getUser', 'AuthController@getUser');
        Route::patch('password/change', 'AuthController@changePassword');
        Route::get('roles_w_permissions', 'AuthController@getRolesWithPermissions');
        Route::get('roles_permissions', 'AuthController@getRolesAndPermissions');
        Route::post('create_role', 'AuthController@createRole');
        Route::put('update_roles_permissions_matrix', 'AuthController@updateRolesPermissionsMatrix');
    });

    // Limit number of requests per seconds, configured in app/Http/Kernel.php
    Route::group([
        'middleware' => 'api',
    ], function () {
        Route::post('password/token/create', 'AuthController@createPasswordResetToken');
        Route::get('password/token/find/{token}', 'AuthController@findPasswordResetToken');
        Route::patch('password/reset', 'AuthController@resetPassword');
    });
});

// Users API
Route::group([
    'prefix' => 'users',
    'middleware' => 'jwt.auth'
], function() {
    Route::post('/collection:batchDelete', 'UserController@batchDelete');
    Route::get('/', 'UserController@index');
    Route::post('/', 'UserController@store');
    Route::patch('/{id}', 'UserController@update');
    Route::patch('/{id}/ban', 'UserController@ban');
    Route::patch('/{id}/unban', 'UserController@unban');
    Route::delete('/{id}', 'UserController@delete');
    Route::get('/registered_user_stats', 'UserController@registeredUserStats');
});
