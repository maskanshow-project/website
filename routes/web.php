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

Route::get('/panel/{path?}', function () {
    if (url('/') !== env('APP_URL')) {
        return redirect(env('APP_URL'));
    }

    $dashboard_template = 'black';

    return view("dashboards.{$dashboard_template}");
})->where('path', '.*');

Route::get('/{path?}', function () {
    if (url('/') !== env('APP_URL')) {
        return redirect(env('APP_URL'));
    }

    $dashboard_template = 'main';

    return view("themplates.{$dashboard_template}");
})->where('path', '.*');
