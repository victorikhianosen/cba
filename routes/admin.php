<?php

use App\Http\Controllers\Admin\AccountOfficerController;
use App\Http\Controllers\Admin\AccountProductController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\GeneralLedgerController;
use App\Http\Controllers\Admin\InvestmentProductController;
use App\Http\Controllers\Admin\LoanProductController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('2fa/verify', 'verifyTwoFactor');
});

Route::middleware(['auth:user'])->group(function () {
    Route::prefix('branches')->controller(BranchController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('{id}', 'show');
        Route::put('{id}', 'update');
        Route::delete('{id}', 'destroy');
        Route::post('{id}/status', 'updateStatus');
    });

    Route::prefix('users')->controller(UserController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('{id}', 'show');
        Route::put('{id}', 'update');
        Route::delete('{id}', 'destroy');
        Route::post('{id}/status', 'updateStatus');
        Route::post('{id}/roles', 'assignRoles');
        Route::delete('{id}/roles', 'removeRoles');
    });

    Route::prefix('roles')->controller(RoleController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('{id}', 'show');
        Route::put('{id}', 'update');
        Route::delete('{id}', 'destroy');
        Route::put('{id}/assign', 'syncPermissions');
    });

    Route::prefix('permissions')->controller(PermissionController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('{id}', 'show');
        Route::put('{id}', 'update');
        Route::delete('{id}', 'destroy');
    });

    Route::prefix('currencies')->controller(CurrencyController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('{id}', 'show');
        Route::put('{id}', 'update');
        Route::delete('{id}', 'destroy');
        Route::post('{id}/status', 'updateStatus');
    });

    Route::prefix('account-officers')->controller(AccountOfficerController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('{id}', 'show');
        Route::put('{id}', 'update');
        Route::delete('{id}', 'destroy');
    });

    Route::prefix('account-products')->controller(AccountProductController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('{id}', 'show');
        Route::put('{id}', 'update');
        Route::delete('{id}', 'destroy');
        Route::post('{id}/approve', 'approve');
        Route::post('{id}/status', 'updateStatus');
    });

    Route::prefix('loan-products')->controller(LoanProductController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('{id}', 'show');
        Route::put('{id}', 'update');
        Route::delete('{id}', 'destroy');
        Route::post('{id}/approve', 'approve');
        Route::post('{id}/status', 'updateStatus');
    });

    Route::prefix('investment-products')->controller(InvestmentProductController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('{id}', 'show');
        Route::put('{id}', 'update');
        Route::delete('{id}', 'destroy');
        Route::post('{id}/approve', 'approve');
        Route::post('{id}/status', 'updateStatus');
    });

    Route::prefix('customers')->controller(CustomerController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('{id}', 'show');
        Route::put('{id}', 'update');
        Route::post('{id}/approve', 'approve');
        Route::post('{id}/reject', 'reject');
        Route::post('{id}/close', 'close');
        Route::put('{customerId}/documents/{documentId}', 'updateDocument');
    });

    Route::prefix('general-ledgers')->controller(GeneralLedgerController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('{id}', 'show');
        Route::put('{id}', 'update');
        Route::delete('{id}', 'destroy');
        Route::post('{id}/status', 'updateStatus');
    });

    Route::prefix('auth')->controller(AuthController::class)->group(function () {
        Route::post('logout', 'logout');
    });
});
