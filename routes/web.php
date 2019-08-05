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

Route::get('/perms/create', function () {

    \Artisan::call('cache:clear');

    $createAccessCode =\App\Permission::create([
        'name' => 'create-access-code',
        'display_name' => 'ساخت کد دسترسی موقت',
        'description' => 'امکان ساخت کد دسترسی جهت قفل کردن یک سیستم جدید روی حساب'
    ]);

    $resetPasswordUser = \App\Permission::create([
        'name' => 'reset-password-user',
        'display_name' => 'تغییر رمز عبور',
        'description' => 'امکان تغییر رمز عبور دیگر کاربران'
    ]);

    $seeSessionsUser = \App\Permission::create([
        'name' => 'see-sessions-user',
        'display_name' => 'مشاهده نشست های کاربر',
        'description' => 'امکان مشاهده اطلاعات سیستم های متصل به اکانت ها'
    ]);

    $freeAccountUser = \App\Permission::create([
        'name' => 'free-account-user',
        'display_name' => 'آزاد بودن حساب',
        'description' => 'حساب کاربر روی هیچ سیستمی قفل نشود'
    ]);
});

Route::get('/perms/attach', function () {

    $role = \App\Role::whereName('owner')->first();

    $role->attachPermission('create-access-code');
    $role->attachPermission('reset-password-user');
    $role->attachPermission('see-sessions-user');
    $role->attachPermission('free-account-user');
});

Route::get('/panel/{path?}', function() {
    $dashboard_template = 'black';

    return view("dashboards.{$dashboard_template}");
})->where('path', '.*');

Route::get('/{path?}', function() {

    $dashboard_template = 'main';

    return view("themplates.{$dashboard_template}");
})->where('path', '.*');