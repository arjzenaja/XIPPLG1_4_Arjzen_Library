<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ReviewController;

Route::apiResource('books', BookController::class);
Route::apiResource('users', UserController::class);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('loans', LoanController::class);
Route::apiResource('reviews', ReviewController::class);

Route::patch('loans/{id}/update-dates', [LoanController::class, 'updateDates']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
