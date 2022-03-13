<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
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
Route::get('/auth/profile', [AuthController::class, 'profile'])->name('auth.profile');
Route::post('/auth/loginAction', [AuthController::class, 'loginAction'])->name('auth.login_action');
Route::get('/auth/register', [AuthController::class, 'create'])->name('auth.register');
Route::post('/auth/register', [AuthController::class, 'store'])->name('auth.store_register');
Route::get('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
Route::get('/auth/edit', [AuthController::class, 'edit'])->name('auth.edit');
Route::post('/auth/update', [AuthController::class, 'update'])->name('auth.update');
Route::get('/auth/changePassword', [AuthController::class, 'viewChangePassword'])->name('auth.viewChangePassword');
Route::post('/auth/changePassword', [AuthController::class, 'changePassword'])->name('auth.changePassword');
Route::get('/auth/forgotPassword', [AuthController::class, 'viewForgotPassword'])->name('auth.viewForgotPassword');
Route::post('/auth/forgotPassword', [AuthController::class, 'forgotPassword'])->name('auth.forgotPassword');
Route::get('/auth/resetPassword/{token}', [AuthController::class, 'viewResetPassword'])->name('auth.viewResetPassword');
Route::post('/auth/resetPassword', [AuthController::class, 'resetPassword'])->name('auth.resetPassword');

//post
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/mypost', [PostController::class, 'myPost'])->name('posts.myPost');
Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
Route::get('/posts/{id}/edit', [PostController::class, 'edit'])->name('posts.edit');
Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');

Route::post('/posts/{id}/update', [PostController::class, 'update'])->name('posts.update');
Route::post('/posts/create', [PostController::class, 'store'])->name('posts.createPost');





