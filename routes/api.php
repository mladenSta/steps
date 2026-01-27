<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('steps/store', [App\Http\Controllers\api\StepController::class, 'store'])->name('steps.store');
Route::get('steps/results', [App\Http\Controllers\api\StepController::class, 'results'])->name('steps.results');

