<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
})->middleware('auth:sanctum');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index']);


Route::post('/import-users', [UserController::class, 'import'])->name('import.users');
Route::view('/email-sender', 'emailsender')->middleware('auth');


// Route::get('/', function () {
//     return view('welcome');
// })->middleware('auth:sanctum');

// // Example of a protected API route
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });