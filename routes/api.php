<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\WorkingHoursController;
use Illuminate\Support\Facades\Route;

Route::prefix('appointment')->name('appointment.')->group(function () {
    Route::post('/store', [AppointmentController::class, 'store'])
        ->name('store');

    Route::get('/slots', [AppointmentController::class, 'slots'])
        ->name('slots');
});

Route::prefix('working-hours')->name('working-hours.')->group(function () {
    // PUT /working-hours        → working-hours.update
    Route::put('/update', [WorkingHoursController::class, 'update'])
        ->name('update');

    Route::get('/list', [WorkingHoursController::class, 'list'])
        ->name('list');
});
