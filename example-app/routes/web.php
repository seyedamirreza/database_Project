<?php

use App\Http\Controllers\TicketController;
use App\Http\Controllers\userController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
return view('welcome');
});

Route::post('/signUp', [UserController::class, 'signUp'])->withoutMiddleware([VerifyCsrfToken::class]);
Route::post('/signIn', [UserController::class, 'signIn'])->withoutMiddleware([VerifyCsrfToken::class]);
Route::post('/verifySignIn', [UserController::class, 'verifySignIn'])->withoutMiddleware([VerifyCsrfToken::class]);
Route::put('/editUser', [UserController::class, 'editUser'])->withoutMiddleware([VerifyCsrfToken::class]);

Route::get('/searchTicket',[TicketController::class, 'searchTicket']);
