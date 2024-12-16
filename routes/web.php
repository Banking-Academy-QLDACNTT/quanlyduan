<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaymentSlipController;
use App\Http\Middleware\Admin;
use Illuminate\Support\Facades\DB;



Route::get('/', [AdminController::class, 'admin_login'])->name('admin_login');
Route::post('/admin/login', [AdminController::class, 'loginPost'])->name('admin.loginPost');

Route::middleware([Admin::class])->group(function () {
    Route::get('/admin/logout', [AdminController::class, 'admin_logout'])->name('admin.logout');
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
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

    Route::get('/admin/all-paymentslip', [PaymentSlipController::class, 'all_paymentslip'])->name('admin.paymentslip');
    Route::get('/admin/add-paymentslip', [PaymentSlipController::class, 'add_paymentslip'])->name('admin.add.paymentslip');
    Route::post('/admin/save-paymentslip', [PaymentSlipController::class, 'save_paymentslip'])->name('admin.save.paymentslip');
    Route::get('admin/paymentslip/{id}/view', [PaymentSlipController::class, 'view_paymentslip'])->name('admin.view.paymentslip');
    Route::get('admin/paymentslip/{id}/edit', [PaymentSlipController::class, 'edit_paymentslip'])->name('admin.edit.paymentslip');
    Route::post('admin/paymentslip/{id}/update', [PaymentSlipController::class, 'update_paymentslip'])->name('admin.update.paymentslip');
    Route::get('admin/paymentslip/{id}/delete', [PaymentSlipController::class, 'delete_paymentslip'])->name('admin.delete.paymentslip');
    Route::get('/admins-exportpaymentslip', [PaymentSlipController::class, 'export_paymentslip'])->name('admins.export.paymentslip');

});