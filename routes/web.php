<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

// Show users
Route::get('/', [UserController::class, 'index']);

// Add user
Route::post('/save-user', [UserController::class, 'store']);

// Delete user
Route::delete('/delete-user/{id}', [UserController::class, 'destroy']);