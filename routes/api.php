<?php

use App\Models\Branch;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportApiController;
use App\Http\Controllers\Api\DealsApiController;
use App\Http\Controllers\Api\TasksApiController;
use App\Http\Controllers\Api\UsersApiController;
use App\Http\Controllers\Api\BranchesApiController;
use App\Http\Controllers\Api\CalendarApiController;
use App\Http\Controllers\Api\DashboardApiContoller;
use App\Http\Controllers\Api\ServicesApiController;
use App\Http\Controllers\Api\IndustriesApiController;
use App\Http\Controllers\Api\ReportApiController as ApiReportApiController;

Route::apiResource('v1/users', UsersApiController::class)->only([
  'index', 'store', 'update', 'destroy'
]);
Route::put('v1/users/{id}/toggle-status', [UsersApiController::class, 'toggleStatus']);

Route::apiResource('v1/branches', BranchesApiController::class)->only([
  'index', 'store', 'update', 'destroy'
]);

Route::apiResource('v1/tasks', TasksApiController::class)->only([
  'index', 'store', 'update', 'destroy'
]);

Route::apiResource('v1/calendar', CalendarApiController::class)->only([
  'index', 'store', 'update', 'destroy'
]);

Route::apiResource('v1/deals', DealsApiController::class)->only([
  'index', 'store', 'update', 'destroy'
]);

Route::put('v1/deals/{deal}/update-status', [DealsApiController::class, 'updateStatus'])
->name('deals.updateStatus');
// routes/api.php
Route::post('/deals/search', [DealsApiController::class, 'search']);
Route::post('/upload', [DealsApiController::class, 'upload']);
Route::post('/reports/generate', [ApiReportApiController::class, 'generate']);


Route::apiResource('v1/industries', IndustriesApiController::class)->only([
  'index', 'store', 'update', 'destroy'
]);

Route::apiResource('v1/services', ServicesApiController::class)->only([
  'index', 'store', 'update', 'destroy'
]);

Route::get('v1/service-statistics',[ServicesApiController::class,'statistics']);


Route::get('v1/dealsAnnually', [DashboardApiContoller::class, 'dealsAnnually']);
Route::get('v1/dealStatusCounts', [DashboardApiContoller::class, 'dealStatusCounts']);


Route::get('v1/statistics', [DashboardApiContoller::class, 'statistics']);