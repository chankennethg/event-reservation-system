<?php

use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\RegisterUserController;
use Illuminate\Support\Facades\Route;

/**
 * ----------------------------------------
 * Guest routes
 * Prefix /auth
 * ----------------------------------------
 */

Route::post('login', [LoginController::class, 'login']);
Route::post('register', [RegisterUserController::class, 'store']);
