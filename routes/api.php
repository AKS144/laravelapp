<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/wallet-balance', [EmailController::class, 'getWalletBalance']);
    Route::post('/send-emails', [EmailController::class, 'sendEmails']);
});