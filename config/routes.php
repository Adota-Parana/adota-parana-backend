<?php

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\AdminController;
use App\Controllers\PetController;
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


Route::middleware('admin')->group(function() {
    Route::get('/admin/dashboard', [AdminController::class, 'index']);

    Route::post('/admin/users/delete/{id}', [AdminController::class, 'usersDelete']);
});

Route::middleware('auth')->group(function() {
    Route::get('/user/dashboard', [UserController::class, 'index']);

    Route::get('/user/profile', [UserController::class, 'edit']);

    Route::post('/user/profile/update', [UserController::class, 'update']);

    Route::get('/feed', [PetController::class, 'index'])->name('feed');
    Route::get('/pets/create', [PetController::class, 'create']);
    Route::post('/pets', [PetController::class, 'store']);
    Route::get('/pets/{id}/edit', [PetController::class, 'edit']);
    Route::post('/pets/{id}/update', [PetController::class, 'update']);
    Route::post('/pets/{id}/delete', [PetController::class, 'destroy']);
});