<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);

Route::post('login', [AuthController::class, 'login']);

// Endpoint yang diproteksi menggunakan middleware jwt
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get("/me", [AuthController::class, 'getUser']);
    Route::get("/account", [AccountController::class, 'index']);
    Route::post("/account", [AccountController::class, 'store']);
    Route::get("/account/{account}", [AccountController::class, 'show']);
    Route::put("/account/{account}", [AccountController::class, 'update']);
    Route::delete("/account/{account}", [AccountController::class, 'destroy']);

    Route::get('/category', [CategoriesController::class, 'index']);
    Route::post('/category', [CategoriesController::class, 'store']);
    Route::put('/category/{category}', [CategoriesController::class, 'update']);
    Route::get('/category/{category}', [CategoriesController::class, 'show']);
    Route::delete('/category/{category}', [CategoriesController::class, 'delete']);

    Route::get('/transaction', [TransactionController::class, 'index']);
    Route::post('/transaction', [TransactionController::class, 'store']);
    Route::get('/transaction/{transaction}', [TransactionController::class, 'show']);
    Route::put('/transaction/{transaction}', [TransactionController::class, 'update']);
    Route::delete('/transaction/{transaction}', [TransactionController::class, 'delete']);

});
