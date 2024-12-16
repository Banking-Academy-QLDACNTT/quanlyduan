<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Middleware\Admin;
use App\Http\Middleware\CheckPermission;

Route::get('/', [AdminController::class, 'admin_login'])->name('admin_login');
Route::post('/admin/login', [AdminController::class, 'loginPost'])->name('admin.loginPost');

Route::middleware([CheckPermission::class . ':1,can_view,can_edit,can_delete,can_add'])->group(function () {
    Route::get('/admin/accounts', [AdminController::class, 'all_accounts'])->name('admin.accounts');
    Route::get('/admin/add-account', [AdminController::class, 'add_account'])->name('admin.add.account');
    Route::post('/admin/save-account', [AdminController::class, 'save_account'])->name('admin.save.account');
    Route::get('admin/account/{id}/delete', [AdminController::class, 'delete_account'])->name('admin.delete.account');
    Route::get('admin/account/{id}/edit', [AdminController::class, 'edit_account'])->name('admin.edit.account');
    Route::post('admin/account/{id}/update', [AdminController::class, 'update_account'])->name('admin.update.account');
    Route::get('/admin/info-admin', [AdminController::class, 'info_admin'])->name('admin.info.admin');
    Route::post('/admin/save-info-admin', [AdminController::class, 'save_info_admin'])->name('admin.save.info');
    Route::get('admin/account/{id}/password', [AdminController::class, 'password_account'])->name('admin.password.account');
    Route::post('/admin/account/{id}/changepassword', [AdminController::class, 'changePassword'])->name('admin.changepassword.account');
    Route::get('/admins-export', [AdminController::class, 'export'])->name('admins.export');

});

Route::middleware([Admin::class])->group(function () {
    Route::get('/admin/logout', [AdminController::class, 'admin_logout'])->name('admin.logout');
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    Route::get('/admin/all-customer', [CustomerController::class, 'all_customer'])->name('admin.customer');
    Route::get('/admin/add-customer', [CustomerController::class, 'add_customer'])->name('admin.add.customer');
    Route::post('/admin/save-customer', [CustomerController::class, 'save_customer'])->name('admin.save.customer');
    Route::get('admin/customer/{id}/view', [CustomerController::class, 'view_customer'])->name('admin.view.customer');
    Route::get('admin/customer/{id}/edit', [CustomerController::class, 'edit_customer'])->name('admin.edit.customer');
    Route::post('admin/customer/{id}/update', [CustomerController::class, 'update_customer'])->name('admin.update.customer');
    Route::get('admin/customer/{id}/delete', [CustomerController::class, 'delete_customer'])->name('admin.delete.customer');
});

    Route::post('/import-excel', [AdminController::class, 'importExcel'])->name('admin.import.account');
