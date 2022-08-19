<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomAuthController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [CustomAuthController::class, 'registerShow']);
Route::post('/register-store', [CustomAuthController::class, 'registerStore'])->name('register');

Route::get('/email_varify/{id}', [CustomAuthController::class, 'emailvarify']);
