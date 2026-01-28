<?php

use App\Http\Controllers\SecurimageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Captcha
Route::get('captcha', SecurimageController::class . '@show');


// Demo Index - View Integration Showcase
Route::get('/demo', function () {
    return view('demo-index');
});

// Demo routes untuk testing integrasi Laravel-CI3
Route::get('/demo-laravel', [App\Http\Controllers\DemoLaravelController::class, 'index'])->name('demo.laravel');
Route::get('/demo-laravel/api', [App\Http\Controllers\DemoLaravelController::class, 'api'])->name('demo.laravel.api');
Route::get('/demo-laravel/session-set', [App\Http\Controllers\DemoLaravelController::class, 'sessionSet'])->name('demo.laravel.session.set');
Route::get('/demo-laravel/session-get', [App\Http\Controllers\DemoLaravelController::class, 'sessionGet'])->name('demo.laravel.session.get');
Route::get('/demo-laravel/session-set-from-laravel', [App\Http\Controllers\DemoLaravelController::class, 'sessionSetFromLaravel'])->name('demo.laravel.session.set.laravel');
Route::get('/demo-laravel/ci3-view', [App\Http\Controllers\DemoLaravelController::class, 'ci3View'])->name('demo.laravel.ci3view');
