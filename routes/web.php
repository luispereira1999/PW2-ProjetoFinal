<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [PostController::class, 'index']);

// Route::get('/user/', [Controller::class, 'index']);

Route::get('/auth', [AuthController::class, 'index']);
Route::post('/auth/signup', [AuthController::class, 'signup']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::get('/auth/logout', [AuthController::class, 'logout']);

Route::get('/posts/{postId}', [PostController::class, 'show']);

Route::get('/profile/{userId}', [UserController::class, 'index']);
Route::get('/account', [UserController::class, 'show']);



//Route::get('/posts', [PostController::class, 'index']);

// Route::resource('/',  [PostController::class, 'index']);
// Route::resource('posts', PostController::class);
