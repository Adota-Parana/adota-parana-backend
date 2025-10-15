<?php

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\AdminController;
use App\Middleware\Admin;
use Core\Router\Route;
use Core\Router\RouteWrapperMiddleware;
use App\Controllers\UserController;

Route::get('/', [HomeController::class, 'index'])->name('root');

Route::get('/register', [AuthController::class, 'showRegistrationForm']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLoginForm']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);


Route::group(['middleware' => ['Authenticate', 'Admin']], function() {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);

    Route::get('/admin/users', [AdminController::class, 'usersIndex']);

    Route::post('/admin/users/delete/{id}', [AdminController::class, 'usersDelete']);
});

Route::group(['middleware' => ['Authenticate']], function() {
    Route::get('/dashboard', [UserController::class, 'dashboard']);

    Route::get('/user/profile', [UserController::class, 'editProfile']);

    Route::post('/user/profile/update', [UserController::class, 'updateProfile']);
});