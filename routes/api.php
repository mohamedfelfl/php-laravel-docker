<?php

use App\Http\Controllers\MealController;
use App\Http\Controllers\OffersController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'user',], function () {
    Route::post('/reset', [UserController::class, 'resetPassword'])->middleware('guest');
    Route::post('/save', [UserController::class, 'save']);
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/custom-login', [UserController::class, 'customLogin']);
    Route::middleware('auth:sanctum')->get('/get', [UserController::class, 'getUserData']);
    //Route::middleware('auth:sanctum')->post('/mail', [UserController::class, 'changeEmail']);
});
Route::group(['prefix' => 'offers'] , function (){
    Route::post('/add', [OffersController::class, 'add']);
    Route::post('/update', [OffersController::class, 'update']);
    Route::post('/delete', [OffersController::class, 'delete']);
    Route::post('/get', [OffersController::class, 'get']);
    Route::middleware('auth:sanctum')->get('/all', [OffersController::class, 'allOffers']);
});
Route::group(['prefix' => 'meals'] , function (){
    Route::post('/add', [MealController::class, 'add']);
    Route::post('/update', [MealController::class, 'update']);
    Route::post('/delete', [MealController::class, 'delete']);
    Route::post('/get', [MealController::class, 'get']);
    Route::middleware('auth:sanctum')->post('/favourite', [MealController::class, 'addMealToFavourite']);
    Route::middleware('auth:sanctum')->get('/all', [MealController::class, 'allMeals']);
});
