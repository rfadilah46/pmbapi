<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\FormulirController;
use App\Http\Controllers\API\Admin\PendaftarController;
use App\Http\Controllers\API\Admin\ProdiController;
use App\Http\Controllers\API\Admin\PengumumanController;
use App\Http\Controllers\API\Admin\BiayaPendaftaranController;
use App\Http\Controllers\API\Admin\TahunAjaranController;
use App\Http\Controllers\API\Admin\PembukaanProdiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    // Public Authentication Routes
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Protected Routes
    Route::middleware('auth:api')->group(function () {
        // Auth Routes
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/profile', [AuthController::class, 'profile']);

        // Mahasiswa Routes
        Route::middleware('role:mahasiswa')->group(function () {
            // Formulir Routes
            Route::post('/formulir', [FormulirController::class, 'store']);
            Route::get('/formulir', [FormulirController::class, 'show']);
            Route::put('/formulir/upload-bukti-bayar', [FormulirController::class, 'uploadBuktiBayar']);
        });

        // Admin Routes
        Route::middleware('role:admin')->prefix('admin')->group(function () {
            // Pendaftar Management
            Route::get('/pendaftar', [PendaftarController::class, 'index']);
            Route::get('/pendaftar/{id}', [PendaftarController::class, 'show']);
            Route::patch('/pendaftar/{id}/verifikasi', [PendaftarController::class, 'verifikasi']);
            Route::get('/pendaftar/{id}/dokumen', [PendaftarController::class, 'downloadDokumen']);

            // Program Studi Management
            Route::apiResource('/prodi', ProdiController::class);

            // Pengumuman Management
            Route::apiResource('/pengumuman', PengumumanController::class);

            // Biaya Pendaftaran Management
            Route::apiResource('/biaya', BiayaPendaftaranController::class);

            // Tahun Ajaran Management
            Route::apiResource('/tahun-ajaran', TahunAjaranController::class);
            Route::patch('/tahun-ajaran/{id}/set-aktif', [TahunAjaranController::class, 'setAktif']);

            // Pembukaan Prodi Management
            Route::apiResource('/pembukaan-prodi', PembukaanProdiController::class);
        });
    });

    // Public Routes
    Route::get('/pengumuman/published', [PengumumanController::class, 'published']);
    Route::get('/prodi/available', [ProdiController::class, 'available']);
    Route::get('/biaya/current', [BiayaPendaftaranController::class, 'current']);
});
