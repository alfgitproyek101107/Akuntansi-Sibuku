<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WhatsAppReportController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// WhatsApp Report Routes
Route::middleware(['auth'])->group(function () {
    // Report schedule management
    Route::prefix('whatsapp-reports')->group(function () {
        Route::get('/', [WhatsAppReportController::class, 'index'])->name('api.whatsapp-reports.index');
        Route::post('/', [WhatsAppReportController::class, 'store'])->name('api.whatsapp-reports.store');
        Route::put('/{schedule}', [WhatsAppReportController::class, 'update'])->name('api.whatsapp-reports.update');
        Route::delete('/{schedule}', [WhatsAppReportController::class, 'destroy'])->name('api.whatsapp-reports.destroy');

        // Manual report sending
        Route::post('/send-manual', [WhatsAppReportController::class, 'sendManual'])->name('api.whatsapp-reports.send-manual');

        // Test connection
        Route::post('/test-connection', [WhatsAppReportController::class, 'testConnection'])->name('api.whatsapp-reports.test-connection');

        // Get available branches
        Route::get('/branches', [WhatsAppReportController::class, 'getBranches'])->name('api.whatsapp-reports.branches');
    });
});