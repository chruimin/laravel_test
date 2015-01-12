<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	// return View::make('hello');
    return 'asdfasdf';
});


Route::get('/test', function()
{
    // return 'hahahahahahahaha';
    print_r(Sentry::getUserProvider());    
    print_r(SentryAdmin::getUserProvider());
});
