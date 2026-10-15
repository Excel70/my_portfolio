<?php

use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/skills', [HomeController::class, 'skills'])->name('skills');
Route::get('/services', [HomeController::class, 'services'])->name('services');
Route::get('/projects', [HomeController::class, 'projects'])->name('projects.index');
Route::get('/projects/{project:slug}', [HomeController::class, 'project'])->name('projects.show');
Route::get('/designs', [HomeController::class, 'designs'])->name('designs');
Route::get('/web3', [HomeController::class, 'web3'])->name('web3');
Route::get('/articles', [HomeController::class, 'articles'])->name('articles.index');
Route::get('/articles/{article:slug}', [HomeController::class, 'article'])->name('articles.show');
Route::get('/contact', [ContactController::class, 'create'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:8,1')->name('contact.store');

Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/admin/login', [AuthenticatedSessionController::class, 'store'])->middleware('throttle:5,1')->name('login.store');
});

Route::middleware(['auth', 'can:access-admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::get('/messages', [SettingController::class, 'messages'])->name('messages.index');
    Route::put('/messages/{message}', [SettingController::class, 'updateMessage'])->name('messages.update');
    Route::delete('/messages/{message}', [SettingController::class, 'destroyMessage'])->name('messages.destroy');
    Route::get('/{resource}', [ContentController::class, 'index'])->whereIn('resource', ['projects', 'skills', 'services', 'designs', 'articles'])->name('content.index');
    Route::get('/{resource}/create', [ContentController::class, 'create'])->whereIn('resource', ['projects', 'skills', 'services', 'designs', 'articles'])->name('content.create');
    Route::post('/{resource}', [ContentController::class, 'store'])->whereIn('resource', ['projects', 'skills', 'services', 'designs', 'articles'])->name('content.store');
    Route::get('/{resource}/{id}/edit', [ContentController::class, 'edit'])->whereIn('resource', ['projects', 'skills', 'services', 'designs', 'articles'])->name('content.edit');
    Route::put('/{resource}/{id}', [ContentController::class, 'update'])->whereIn('resource', ['projects', 'skills', 'services', 'designs', 'articles'])->name('content.update');
    Route::delete('/{resource}/{id}', [ContentController::class, 'destroy'])->whereIn('resource', ['projects', 'skills', 'services', 'designs', 'articles'])->name('content.destroy');
});
