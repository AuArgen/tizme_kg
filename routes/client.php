<?php

use App\Http\Controllers\GuestController;
use Illuminate\Support\Facades\Route;

Route::prefix('client')->name('client.')->middleware('auth')->group(function () {
    Route::get('/', [GuestController::class, 'index'])->name('index');

    // Folder Routes
    Route::post('/folders', [GuestController::class, 'storeFolder'])->name('folders.store');
    Route::put('/folders/{folder}', [GuestController::class, 'updateFolder'])->name('folders.update');
    Route::delete('/folders/{folder}', [GuestController::class, 'destroyFolder'])->name('folders.destroy');

    // Guest Routes
    Route::post('/guests', [GuestController::class, 'storeGuest'])->name('guests.store');
    Route::put('/guests/{guest}', [GuestController::class, 'updateGuest'])->name('guests.update');
    Route::delete('/guests/{guest}', [GuestController::class, 'destroyGuest'])->name('guests.destroy');

    // Invitation Upload Route
    Route::post('/upload-invitation', [GuestController::class, 'uploadInvitation'])->name('invitation.upload');
});
