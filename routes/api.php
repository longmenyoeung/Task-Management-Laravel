<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TaskController;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



//public routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);



// ============ Authenticate ============================
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', [AuthController::class, 'index'])->middleware('role:admin');
    Route::get('/users/me', [AuthController::class, 'me']);
    Route::post('/users/logout', [AuthController::class, 'logout']);

    // ============= Category CRUD OPERATION =============
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::post('/', [CategoryController::class, 'store']);
        Route::get('/{id}', [CategoryController::class, 'show']);
        Route::put('/{id}', [CategoryController::class, 'update']);
        Route::delete('/{id}', [CategoryController::class, 'destroy']);
    });


    // ============= Task CRUD OPERATION ===============
    Route::prefix('tasks')->group( function () {
        Route::get('/', [TaskController::class, 'index']);
        Route::get('/{id}', [TaskController::class, 'show']);
        Route::post('/', [TaskController::class, 'store']);
        Route::put('/{id}', [TaskController::class, 'update']);
        Route::delete('/{id}', [TaskController::class, 'destroy']);
    });


    //creat admin
    // Route::middleware(['auth:sanctum', 'role:admin'])
    // ->prefix('admin')
    // ->group(function () {
    //     Route::post('/create', [AdminController::class, 'store']);
    // });
    Route::prefix('admin')->group(function () {
        Route::post('/create', [AdminController::class, 'store']);
    })->middleware('role:admin');

});