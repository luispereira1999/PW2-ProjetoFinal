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

// PÁGINA INICIAL
Route::get('/', [PostController::class, 'index']);


// AUTENTICAÇÃO
Route::get('/auth', [AuthController::class, 'index'])
    ->name('auth');

Route::post('/auth/signup', [AuthController::class, 'signup'])
    ->name('auth.signup');

Route::post('/auth/login', [AuthController::class, 'login'])
    ->name('auth.login');

Route::get('/auth/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('auth.logout');


// UTILIZADORES
Route::get('/profile/{userId}', [UserController::class, 'index'])
    ->middleware('auth')
    ->name('profile');

Route::get('/account', [UserController::class, 'show'])
    ->middleware('auth')
    ->name('account');

Route::patch('/account/edit-data/{userId}', [UserController::class, 'updateData'])
    ->middleware('auth')
    ->name('account.edit-data');

Route::patch('/account/edit-password/{userId}', [UserController::class, 'updatePassword'])
    ->middleware('auth')
    ->name('account.edit-password');


// POSTS
Route::get('/posts/{postId}', [PostController::class, 'show'])
    ->middleware('check.post.exists')
    ->name('posts');

Route::patch('/posts/create/{postId}', [PostController::class, 'store'])
    ->middleware(['auth'])
    ->name('posts.create');

Route::patch('/posts/update/{postId}', [PostController::class, 'update'])
    ->middleware(['auth', 'check.post.exists'])
    ->name('posts.update');

Route::post('/posts/vote/{postId}', [PostController::class, 'vote'])
    ->middleware(['auth', 'check.post.exists'])
    ->name('posts.vote');

Route::delete('/posts/delete/{postId}', [PostController::class, 'destroy'])
    ->middleware(['auth', 'check.post.exists'])
    ->name('posts.delete');


// COMENTÁRIOS
Route::get('/comments', [CommentController::class, 'index'])
    ->middleware('auth')
    ->name('comments');

Route::post('/comments/create', [CommentController::class, 'create'])
    ->middleware('auth')
    ->name('comments.create');

Route::patch('/comments/update/{postId}', [CommentController::class, 'update'])
    ->middleware('auth')
    ->name('comments.update');

Route::delete('/comments/delete/{postId}', [CommentController::class, 'destroy'])
    ->middleware('auth')
    ->name('comments.delete');
