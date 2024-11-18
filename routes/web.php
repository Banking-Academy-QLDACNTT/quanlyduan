<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\Admin;


Route::get('/admin/login', [AdminController::class, 'admin_login'])->name('admin_login');
Route::post('/admin/login', [AdminController::class, 'loginPost'])->name('admin.loginPost');
Route::get('/admin/logout', [AdminController::class, 'admin_logout'])->name('admin.logout');

Route::middleware([Admin::class])->group(function () {
    Route::get('/', [AdminController::class, 'admin_login'])->name('admin_login');
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});
