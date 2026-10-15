<?php

use App\Http\Controllers\Api\PortfolioApiController;
use Illuminate\Support\Facades\Route;

Route::get('/projects', [PortfolioApiController::class, 'projects']);
Route::get('/projects/{project:slug}', [PortfolioApiController::class, 'project']);
Route::get('/skills', [PortfolioApiController::class, 'skills']);
Route::get('/services', [PortfolioApiController::class, 'services']);
Route::get('/articles', [PortfolioApiController::class, 'articles']);
