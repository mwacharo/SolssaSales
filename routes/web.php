<?php

use App\Http\Controllers\BranchController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\IndustryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/deals', [DealController::class, 'index'])->name('deals');
    Route::get('/industries', [IndustryController::class, 'index'])->name('industries');
    Route::get('/services', [ServiceController::class, 'index'])->name('services');
    Route::get('/branches', [BranchController::class, 'index'])->name('branches');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks');
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/user-roles', [UserController::class, 'userRoles'])->name('user.roles');
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');

    Route::get('/account', [UserController::class, 'userAcount'])->name('account');
    Route::get('/account/settings', [UserController::class, 'acountSettings'])->name('accountSettings');
});
Auth::routes();