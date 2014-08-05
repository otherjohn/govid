<?php

/*
|--------------------------------------------------------------------------
| Clientlication Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an clientlication.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

/** ------------------------------------------
 *  Route model binding
 *  ------------------------------------------
 */
Route::model('user', 'User');
Route::model('client', 'Client');
Route::model('role', 'Role');

/** ------------------------------------------
 *  Route constraint patterns
 *  ------------------------------------------
 */
Route::pattern('client', '[0-9]+');
Route::pattern('user', '[0-9]+');
Route::pattern('role', '[0-9]+');
Route::pattern('token', '[0-9a-z]+');

/** ------------------------------------------
 *  Admin Routes
 *  ------------------------------------------
 */
Route::group(array('prefix' => 'admin', 'before' => 'auth'), function()
{

    # Client Management
    Route::get('clients/{client}/show', 'AdminClientsController@getShow');
    Route::get('clients/{client}/edit', 'AdminClientsController@getEdit');
    Route::post('clients/{client}/edit', 'AdminClientsController@postEdit');
    Route::get('clients/{client}/delete', 'AdminClientsController@getDelete');
    Route::post('clients/{client}/delete', 'AdminClientsController@postDelete');
    Route::controller('clients', 'AdminClientsController');

    # User Management
    Route::get('users/{user}/show', 'AdminUsersController@getShow');
    Route::get('users/{user}/edit', 'AdminUsersController@getEdit');
    Route::post('users/{user}/edit', 'AdminUsersController@postEdit');
    Route::get('users/{user}/delete', 'AdminUsersController@getDelete');
    Route::post('users/{user}/delete', 'AdminUsersController@postDelete');
    Route::controller('users', 'AdminUsersController');

    # User Role Management
    Route::get('roles/{role}/show', 'AdminRolesController@getShow');
    Route::get('roles/{role}/edit', 'AdminRolesController@getEdit');
    Route::post('roles/{role}/edit', 'AdminRolesController@postEdit');
    Route::get('roles/{role}/delete', 'AdminRolesController@getDelete');
    Route::post('roles/{role}/delete', 'AdminRolesController@postDelete');
    Route::controller('roles', 'AdminRolesController');

    # Admin Dashboard
    Route::controller('/', 'AdminDashboardController');
});


/** ------------------------------------------
 *  Frontend Routes
 *  ------------------------------------------
 */

// User reset routes
Route::get('user/reset/{token}', 'UserController@getReset');
// User password reset
Route::post('user/reset/{token}', 'UserController@postReset');
//:: User Account Routes ::
Route::post('user/{user}/edit', 'UserController@postEdit');

//:: User Account Routes ::
Route::post('user/login', 'UserController@postLogin');

# User RESTful Routes (Login, Logout, Register, etc)
Route::controller('user', 'UserController');


/** ------------------------------------------
 *  Oauth Routes
 *  ------------------------------------------
 *

    oauth/index -> oauth/[controller@index]
    
    oauth/signin -> oauth/signin.blade.php
        In the sign-in form HTML page you should tell the user the name of the client that their signing into.
    
    oauth/authorize -> oauth/authorize.blade.php
        show explicit authorization form
        Once the user has signed in (if they didn’t already have an existing session)
        then they should be redirected the authorise route where the user explicitly gives
        permission for the client to act on their behalf.

        In the authorize form the user should again be told the name of the client
        and also list all the scopes (permissions) it is requesting.
    
    oauth/token -> oauth/[controller@token]

**/

//:: Begin Oauth Process ::
Route::get('oauth', 'OauthController@action_index');
//Route::get('oauth/index', 'OauthController@action_index');

//:: Signin Client ::
Route::get('signin', function(){return View::make('site/oauth/signin');});

//:: Authorize Client ::
Route::get('authorise', function(){return View::make('site/oauth/authorise');});

Route::post('do_authorise', 'OauthController@action_authorise');

//:: Get Access token ::
Route::get('token', 'OauthController@action_access_token');

//:: Verify ID Token ::
Route::get('check', 'OauthController@action_check_id');

//:: UserInfo Endpoint ::
Route::get('userinfo', 'OauthController@action_userinfo');

//:: Provider Configuration Endpoint ::
Route::get('.well-known/openid-configuration', 'OauthController@action_configuration');

//:: Provider Configuration Endpoint ::
Route::get('jwks', 'OauthController@action_jwks');

Route::controller('oauth', 'OauthController');


//:: Clientlication Routes ::

# Filter for detect language
Route::when('contact-us','detectLang');

# Contact Us Static Page
Route::get('contact-us', function()
{
    // Return about us page
    return View::make('site/contact-us');
});

# Posts - Second to last set, match slug
Route::get('{postSlug}', 'ClientController@getView');
Route::post('{postSlug}', 'ClientController@postView');

# Index Page - Last route, no matches
Route::get('/', array('before' => 'detectLang','uses' => 'ClientController@getIndex'));
