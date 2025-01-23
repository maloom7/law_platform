<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\LegalCaseController;
use App\Http\Controllers\API\TaskController;
use App\Http\Controllers\API\ContractController;
use App\Http\Controllers\API\ClientController;
use App\Http\Controllers\API\InvoiceController;
use App\Http\Controllers\API\ServiceController;
use App\Http\Controllers\API\PerformanceIndicatorController;

Route::apiResource('legal-cases', LegalCaseController::class);
Route::apiResource('tasks', TaskController::class);
Route::apiResource('contracts', ContractController::class);
Route::apiResource('clients', ClientController::class);
Route::apiResource('invoices', InvoiceController::class);
Route::apiResource('services', ServiceController::class);
Route::apiResource('performance-indicators', PerformanceIndicatorController::class);

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});