<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\V1\BankTransactionController;
use App\Http\Controllers\V1\ExpenseController;
use App\Http\Controllers\V1\InventoryController;
use App\Http\Controllers\V1\PaymentController;
use App\Http\Controllers\V1\PayrollController;
use App\Http\Controllers\V1\ReportController;
use App\Http\Controllers\V1\RoleController;
use App\Http\Controllers\V1\InvoiceController;
use App\Http\Controllers\V1\TaxController;
use App\Http\Middleware\ClientMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;


Route::get('/', ApiController::class);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('logout', 'logout');
});

Route::get('/roles', RoleController::class);

Route::middleware(['auth:sanctum', ClientMiddleware::class])->group(function () {
    Route::apiResource('/invoice', InvoiceController::class);
    Route::apiResource('/payment', PaymentController::class);
    Route::apiResource('/expenses', ExpenseController::class);
    Route::apiResource('/bankTransaction', BankTransactionController::class);
    Route::apiResource('/report', ReportController::class);
    Route::apiResource('/payroll', PayrollController::class);
    Route::apiResource('/inventory', InventoryController::class);
    Route::apiResource('/tax', TaxController::class);
});