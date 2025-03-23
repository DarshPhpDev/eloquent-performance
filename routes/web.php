<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderReportController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/report', [OrderReportController::class, 'report']);
Route::get('/report/optimized/eloquent', [OrderReportController::class, 'reportOptimizedEloquent']);
Route::get('/report/optimized/query', [OrderReportController::class, 'reportOptimizedQueryBuilder']);
