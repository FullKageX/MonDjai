<?php

use Illuminate\Support\Facades\Route;

    Route::get('/', function () {
        return view('welcome');
    });

    Route::middleware('auth:admin')->group(function () {
        Route::post('/admin/tontines/{tontine}/activer', [App\Http\Controllers\Admin\TontineController::class, 'activer'])
            ->name('admin.tontines.activer');
    });
