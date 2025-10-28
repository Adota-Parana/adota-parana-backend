<?php

use App\Controllers\HomeController;
use App\Controllers\AuthenticationsController;
use App\Controllers\AdminController;
use App\Controllers\PetController;
use App\Controllers\UserController;

use App\Middleware\Admin;

use Core\Router\Route;
use Core\Router\RouteWrapperMiddleware;

// Home route
Route::get('/', [HomeController::class, 'index'])->name('root');

// Authentication routes
Route::get('/register', [AuthenticationsController::class, 'showRegistrationForm']);
Route::post('/register', [AuthenticationsController::class, 'register']);

Route::get('/login', [AuthenticationsController::class, 'showLoginForm']);
Route::post('/login', [AuthenticationsController::class, 'login']);

Route::post('/logout', [AuthenticationsController::class, 'logout']);


// Admin routes
Route::middleware('admin')->group(function() {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/dashboard/{page}', [AdminController::class, 'index'])->name('admin.paginated');
    Route::post('/admin/users/delete/{id}', [AdminController::class, 'usersDelete'])->name('admin.users.delete');
});

// User and Pet routes
Route::middleware('auth')->group(function() {
    // User routes
    Route::get('/user/dashboard', [UserController::class, 'index']);
    Route::get('/user/profile', [UserController::class, 'edit']);
    Route::post('/user/profile/update', [UserController::class, 'update']);

    // Feed route
    Route::get('/home/feed', [PetController::class, 'index'])->name('feed');

    // Pet routes
    Route::post('/pets/store', [PetController::class, 'store']);
    Route::get('/pets/create', [PetController::class, 'create']);
    Route::get('/pets/edit/{id}', [PetController::class, 'edit']);
    Route::post('/pets/update/{id}', [PetController::class, 'update']);
    Route::post('/pets/delete/{id}', [PetController::class, 'destroy']);
});