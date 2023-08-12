<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ErrorController;
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
Route::get('/', [HomeController::class, 'home'])
    ->name('home');

Route::get('/search/{searchText}', [HomeController::class, 'search'])
    ->name('search');


// AUTENTICAÇÃO
Route::get('/auth', [AuthController::class, 'auth'])
    ->middleware([
        'guest'
    ])
    ->name('auth');

Route::post('/auth/login', [AuthController::class, 'login'])
    ->middleware([
        'guest'
    ])
    ->name('auth.login');

Route::post('/auth/signup', [AuthController::class, 'signup'])
    ->middleware([
        'guest'
    ])
    ->name('auth.signup');

Route::get('/auth/logout', [AuthController::class, 'logout'])
    ->middleware([
        'auth'
    ])
    ->name('auth.logout');


// UTILIZADORES
Route::get('/profile/{userId}', [UserController::class, 'profile'])
    ->name('profile');

Route::get('/account/{userId}', [UserController::class, 'account'])
    ->middleware([
        'auth'
    ])
    ->name('account');

Route::patch('/account/edit-data/{userId}', [UserController::class, 'editData'])
    ->middleware([
        'auth'
    ])
    ->name('account.edit-data');

Route::patch('/account/edit-password/{userId}', [UserController::class, 'editPassword'])
    ->middleware([
        'auth'
    ])
    ->name('account.edit-password');

Route::delete('/account/delete/{userId}', [UserController::class, 'delete'])
    ->middleware([
        'auth'
    ])
    ->name('account.delete');


// POSTS
Route::get('/posts/{postId}', [PostController::class, 'post'])
    ->middleware([
        'check.post.exists'
    ])
    ->name('posts');

Route::post('/posts/create', [PostController::class, 'create'])
    ->middleware([
        'auth'
    ])
    ->name('posts.create');

Route::patch('/posts/edit/{postId}', [PostController::class, 'edit'])
    ->middleware([
        'auth',
        'check.post.exists',
        'check.post.belongs.user'
    ])
    ->name('posts.edit');

Route::patch('/posts/vote/{postId}', [PostController::class, 'vote'])
    ->middleware([
        'auth',
        'check.post.exists'
    ])
    ->name('posts.vote');

Route::delete('/posts/delete/{postId}', [PostController::class, 'delete'])
    ->middleware([
        'auth',
        'check.post.exists',
        'check.post.belongs.user'
    ])
    ->name('posts.delete');


// COMENTÁRIOS
Route::post('/comments/create/{postId}', [CommentController::class, 'create'])
    ->middleware([
        'auth',
        'check.post.exists'
    ])
    ->name('comments.create');

Route::patch('/comments/edit/{commentId}', [CommentController::class, 'edit'])
    ->middleware([
        'auth',
        'check.comment.exists',
        'check.comment.belongs.user'
    ])
    ->name('comments.edit');

Route::patch('/comments/vote/{commentId}', [CommentController::class, 'vote'])
    ->middleware([
        'auth',
        'check.comment.exists'
    ])
    ->name('comments.vote');

Route::delete('/comments/delete/{commentId}/{postId}', [CommentController::class, 'delete'])
    ->middleware([
        'auth',
        'check.post.exists',
        'check.comment.exists',
        'check.comment.belongs.user'
    ])
    ->name('comments.delete');


// PÁGINA DE ERRO FATAL
Route::get('/500', [ErrorController::class, 'fatalError'])
    ->name('500');


// QUANDO UMA ROTA NÃO É ENCONTRADA
Route::fallback([ErrorController::class, 'notFound']);
