<?php

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\AdminController;
use App\Middleware\Admin;
use Core\Router\Route;
use Core\Router\RouteWrapperMiddleware;
use App\Controllers\UserController;

Route::get('/register', [AuthController::class, 'showRegistrationForm']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/', [HomeController::class, 'index'])->name('root');
Route::get('/login', [AuthController::class, 'showLoginForm']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);


$adminGroup = new RouteWrapperMiddleware('admin');

$adminGroup->group(function() {
    Route::get('/admin/dashboard', [AdminController::class, 'index']);
});

$authGroup = new RouteWrapperMiddleware('auth');

$authGroup->group(function() {
    Route::get('/user/dashboard', [UserController::class, 'index']);
});