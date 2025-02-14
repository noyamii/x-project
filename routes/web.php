<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReplyingController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/post', [PostController::class, 'store'])->middleware('auth');
Route::get('/post', [PostController::class, 'index']);
Route::get('/post/{id}', [PostController::class, 'show']);
Route::delete('/post/{id}', [PostController::class, 'destroy'])->middleware('auth');

Route::get('/comment/{id}', [CommentController::class, 'index']);
Route::post('/comment/{id}', [CommentController::class, 'store'])->middleware('auth');
Route::delete('/comment/{id}', [CommentController::class, 'destroy'])->middleware('auth');

Route::get('/tag', [TagController::class, 'index']);
Route::delete('/tag/{name}', [TagController::class, 'destroy'])->middleware('auth');

Route::delete('/user/{id}', [UserController::class, 'destroy'])->middleware('auth');
Route::get('/user/{id}', [UserController::class, 'show']);

Route::middleware('guest')->group(function () {
    Route::view('/signup', 'auth.signup');
    Route::post('/signup', [UserController::class, 'store']);

    Route::post('/login', [SessionController::class, 'store']);
    Route::view('/login', 'auth.login');
});

Route::delete('/logout', [SessionController::class, 'destroy'])->middleware('auth');