<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// Muat satu kali dari routes/web.php, di luar grup route lain.
// InventoryReportRequest memeriksa role admin/pegawai dan membatasi CSV ke admin.
Route::middleware('auth')->group(function (): void {
    Route::get('/reports/inventory', [ReportController::class, 'inventory'])
        ->name('reports.inventory');

    Route::get('/reports/inventory/export', [ReportController::class, 'export'])
        ->name('reports.export');
});
