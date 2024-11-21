<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Account;

Route::get('/', [AccountController::class, 'admin_login'])->name('admin.login');

//Route::get('/admin', [AccountController::class, 'admin_login'])->name('admin_login');
// Route::post('/admin/loginpost', [AccountController::class, 'loginPost'])->name('admin.loginPost');



Route::middleware([Account::class])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('view.all.users');
    Route::get('/admin/dashboard', [AccountController::class, 'dashboard'])->name('admin.dashboard');
});