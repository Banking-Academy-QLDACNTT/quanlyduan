<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
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


    Route::get('admin/all-order', [OrderController::class, 'all_order'])->name('admin.order.all');
    Route::get('admin/add-order', [OrderController::class, 'add_order'])->name('admin.add.order');
    Route::post('admin/save-order', [OrderController::class, 'save_order'])->name('admin.save.order');
    Route::get('admin/order/{id}/details', [OrderController::class, 'order_details'])->name('admin.order.details');
    Route::get('admin/order/{id}/edit', [OrderController::class, 'edit_order'])->name('admin.order.edit');
    Route::put('admin/order/{id}/update', [OrderController::class, 'update_order'])->name('admin.update.order');
    Route::get('admin/order/{id}/delete', [OrderController::class, 'delete_order'])->name('admin.delete.order');


    
    Route::get('/admin/all-product', [ProductController::class, 'all_product'])->name('admin.product');
    Route::get('/admin/add-product', [ProductController::class, 'add_product'])->name('admin.add.product');
    Route::post('/admin/save-product', [ProductController::class, 'save_product'])->name('admin.save.product');
    Route::get('admin/product/{id}/view', [ProductController::class, 'view_product'])->name('admin.view.product');
    Route::get('admin/product/{id}/edit', [ProductController::class, 'edit_product'])->name('admin.edit.product');
    Route::post('admin/product/{id}/update', [ProductController::class, 'update_product'])->name('admin.update.product');
    Route::get('admin/product/{id}/delete', [ProductController::class, 'delete_product'])->name('admin.delete.product');
    Route::get('/admins-exportproduct', [ProductController::class, 'export_product'])->name('admins.export.product');
});

    Route::post('/import-excel', [AdminController::class, 'importExcel'])->name('admin.import.account');
