<?php

use App\Controllers\HomeController;
use App\Controllers\AuthenticationController;
use App\Controllers\AdminController;
use App\Middleware\Admin;
use Core\Router\Route;
use Core\Router\RouteWrapperMiddleware;

Route::get('/register', [AuthenticationController::class, 'showRegistrationForm']);
Route::post('/register', [AuthenticationController::class, 'register']);
Route::get('/', [HomeController::class, 'index'])->name('root');
Route::get('/login', [AuthenticationController::class, 'showLoginForm']);
Route::post('/login', [AuthenticationController::class, 'login']);
Route::post('/logout', [AuthenticationController::class, 'logout']);


$adminGroup = new RouteWrapperMiddleware('admin');

$adminGroup->group(function() {
    Route::get('/admin/dashboard', [AdminController::class, 'index']);
});