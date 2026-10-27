<?php

use App\Http\Controllers\AuthController;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



//public routes
Route::post('/auth/register', [AuthController::class, 'register']);

//get list users
Route::get('/users', [AuthController::class, 'index']);