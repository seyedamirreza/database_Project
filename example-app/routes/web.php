<?php

use App\Http\Controllers\userController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
return view('welcome');
});

Route::post('/signup', [UserController::class, 'signup'])->withoutMiddleware([VerifyCsrfToken::class]);

