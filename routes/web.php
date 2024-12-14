<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/post', [PostController::class, 'store']);

Route::post('/user', [UserController::class, 'store']);
Route::get('/test', [UserController::class, 'test']);