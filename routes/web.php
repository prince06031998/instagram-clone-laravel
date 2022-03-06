<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Models\User;
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

Route::get('/', function () {
    return view('welcome');
});

//Auth

Route::get('/auth/login', [AuthController::class, 'index'])->name('auth.login');
Route::post('/auth/loginAction', [AuthController::class, 'loginAction'])->name('auth.login_action');
Route::get('/auth/register', [AuthController::class, 'create'])->name('auth.register');
Route::post('/auth/register', [AuthController::class, 'store'])->name('auth.store_register');
Route::get('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');



