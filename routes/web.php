<?php

use App\Http\Controllers\GraficoController;
use Illuminate\Support\Facades\Route;

Route::get('/migrar', function () {
    Artisan::call('migrate', ['--force' => true]);
    return 'Migraciones completadas';
});
Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
Route::get('/dashboard/grafico',[GraficoController::class,'index'])->name('dashboard.grafico');
