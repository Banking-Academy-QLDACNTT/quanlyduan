<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\Admin;
use App\Http\Controllers\CustomerController;

Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');

Route::middleware([Admin::class])->group(function () {
    Route::get('/admin/all-customer', [CustomerController::class, 'all_customer'])->name('admin.customer');
    Route::get('/admin/add-customer', [CustomerController::class, 'add_customer'])->name('admin.add.customer');
    Route::get('admin/customer/{id}/edit', [CustomerController::class, 'edit_customer'])->name('admin.edit.customer');
    Route::post('admin/customer/{id}/update', [CustomerController::class, 'update_customer'])->name('admin.update.customer');
    Route::get('admin/customer/{id}/delete', [CustomerController::class, 'delete_customer'])->name('admin.delete.customer');
});
