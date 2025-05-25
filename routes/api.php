<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\VideoController;
use Illuminate\Support\Facades\Route;


Route::controller(PostController::class)->group(function () {

    Route::post('/posts/create', 'store');
    Route::post('/posts/{post}', 'update');
    Route::get('/posts/index', 'index');
    Route::delete('/posts/{posts}', 'destroy');
});


Route::controller(CategoryController::class)->group(function () {

    Route::post('/category/create', 'store');
    Route::post('/category/{category}', 'update');
    Route::get('/category/index', 'index');
    Route::delete('/category/{category}', 'destroy');
});


Route::controller(CommentController::class)->group(function () {

    Route::post('/comment/create', 'store');
    Route::post('/comment/{category}', 'update');
    Route::get('/comment/index', 'index');
    Route::delete('/comment/{comment}', 'destroy');
});



Route::controller(TagController::class)->group(function () {

    Route::post('/tag/create', 'store');
    Route::post('/tag/{tag}', 'update');
    Route::get('/tag/index', 'index');
    Route::delete('/tag/{tag}', 'destroy');
});

Route::controller(VideoController::class)->group(function () {

    Route::post('/video/create', 'store');
    Route::post('/video/{video}', 'update');
    Route::get('/video/index', 'index');
    Route::delete('/video/{video}', 'destroy');
});

