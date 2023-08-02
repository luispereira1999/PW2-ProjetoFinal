<?php

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
Route::get('/', [PostController::class, 'index'])
    ->name('home');

Route::get('/search/{searchText}', [PostController::class, 'search'])
    ->name('search');


// AUTENTICAÇÃO
Route::get('/auth', [AuthController::class, 'index'])
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
Route::get('/profile/{userId}', [UserController::class, 'index'])
    ->name('profile');

Route::get('/account', [UserController::class, 'show'])
    ->middleware([
        'auth'
    ])
    ->name('account');

Route::patch('/account/edit-data/{userId}', [UserController::class, 'updateData'])
    ->middleware([
        'auth'
    ])
    ->name('account.edit-data');

Route::patch('/account/edit-password/{userId}', [UserController::class, 'updatePassword'])
    ->middleware([
        'auth'
    ])
    ->name('account.edit-password');


// POSTS
Route::get('/posts/{postId}', [PostController::class, 'show'])
    ->middleware([
        'check.post.exists'
    ])
    ->name('posts');

Route::post('/posts/create', [PostController::class, 'create'])
    ->middleware([
        'auth'
    ])
    ->name('posts.create');

Route::patch('/posts/update/{postId}', [PostController::class, 'update'])
    ->middleware([
        'auth',
        'check.post.exists',
        'check.post.belongs.user'
    ])
    ->name('posts.update');

Route::post('/posts/vote/{postId}', [PostController::class, 'vote'])
    ->middleware([
        'auth',
        'check.post.exists'
    ])
    ->name('posts.vote');

Route::delete('/posts/delete/{postId}', [PostController::class, 'destroy'])
    ->middleware([
        'auth',
        'check.post.exists',
        'check.post.belongs.user'
    ])
    ->name('posts.delete');


// COMENTÁRIOS
Route::post('/comments/create', [CommentController::class, 'create'])
    ->middleware([
        'auth'
    ])
    ->name('comments.create');

Route::patch('/comments/update/{commentId}', [CommentController::class, 'update'])
    ->middleware([
        'auth',
        'check.comment.exists'
    ])
    ->name('comments.update');

Route::post('/comments/vote/{commentId}', [CommentController::class, 'vote'])
    ->middleware([
        'auth',
        'check.comment.exists'
    ])
    ->name('comments.vote');

Route::delete('/comments/delete/{commentId}', [CommentController::class, 'destroy'])
    ->middleware([
        'auth',
        'check.comment.exists'
    ])
    ->name('comments.delete');


// PÁGINAS DE ERROS
Route::get('/500', function () {
    return view('500');
})->name('500');


// QUANDO UMA ROTA NÃO É ENCONTRADA
Route::fallback(function () {
    return view('404');
});
