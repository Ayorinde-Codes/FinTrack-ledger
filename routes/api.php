<?php

use App\Http\Controllers\V1\BankTransactionController;
use App\Http\Controllers\V1\ExpenseController;
use App\Http\Controllers\V1\PaymentController;
use App\Http\Controllers\V1\RoleController;
use App\Http\Controllers\V1\InvoiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('logout', 'logout');
});

Route::get('/roles', RoleController::class);

Route::apiResource('/invoice', InvoiceController::class);
Route::apiResource('/payment', PaymentController::class);
Route::apiResource('/expenses', ExpenseController::class);
Route::apiResource('/bankTransaction', BankTransactionController::class);