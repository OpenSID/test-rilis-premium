<?php

/*
 * Routes untuk module SMS (Laravel Native)
 * 
 * Ini adalah contoh implementasi routing untuk Sms Controller
 * yang sudah di-convert ke Laravel format
 */

use App\Http\Controllers\SmsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth:admin'])->prefix('sms')->name('sms.')->group(function () {
    // Inbox routes
    Route::get('/', [SmsController::class, 'index'])->name('index');
    Route::get('/datatables', [SmsController::class, 'datatables'])->name('datatables');
    Route::get('/form', [SmsController::class, 'form'])->name('form');
    Route::post('/insert', [SmsController::class, 'insert'])->name('insert');
    Route::post('/update/{id}', [SmsController::class, 'update'])->name('update');
    Route::post('/delete', [SmsController::class, 'delete'])->name('delete');

    // Broadcast routes
    Route::get('/broadcast', [SmsController::class, 'broadcast'])->name('broadcast');
    Route::post('/broadcast_proses', [SmsController::class, 'broadcastProses'])->name('broadcast_proses');

    // Hubung Warga routes
    Route::get('/arsip', [SmsController::class, 'arsip'])->name('arsip');
    Route::get('/arsip_datatables', [SmsController::class, 'arsipDatatables'])->name('arsip_datatables');
    Route::get('/kirim', [SmsController::class, 'kirim'])->name('kirim');
    Route::post('/proses_kirim', [SmsController::class, 'prosesKirim'])->name('proses_kirim');
    Route::post('/hubung_delete/{id?}', [SmsController::class, 'hubungDelete'])->name('hubung_delete');
});
