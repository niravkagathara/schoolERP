<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\StudentApiController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/students', [StudentApiController::class, 'getStudents']);
    Route::get('/attendance', [StudentApiController::class, 'getAttendance']);
    Route::get('/homework', [StudentApiController::class, 'getHomework']);
    Route::get('/fees', [StudentApiController::class, 'getFees']);
});
