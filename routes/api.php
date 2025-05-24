<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PostController;
use Illuminate\Support\Facades\Route;


Route::controller(PostController::class)->group(function () {

    Route::post('/posts/create', 'store');
    Route::get('/posts/index', 'index');
});



Route::controller(CategoryController::class)->group(function () {

    Route::post('/category/create', 'store');
    Route::get('/category/index', 'index');
});






     








//   Route::middleware('auth:api')->group(function(){

//    Route::controller(AuthController::class)->group(function(){
//    Route::post('me', [AuthController::class,'me']);
//     Route::post('refresh', [AuthController::class,'refresh']);
  
   
    // });
  
// });
