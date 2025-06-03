<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\userController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
return view('welcome');
});

Route::post('/signUp', [UserController::class, 'signUp'])->withoutMiddleware([VerifyCsrfToken::class])->name('signUp-api2');
Route::post('/signIn', [UserController::class, 'signIn'])->withoutMiddleware([VerifyCsrfToken::class])->name('signIn-api1');
Route::post('/verifySignIn', [UserController::class, 'verifySignIn'])->withoutMiddleware([VerifyCsrfToken::class])->name('verifySignIn-api1-2');
Route::put('/editUser', [UserController::class, 'editUser'])->withoutMiddleware([VerifyCsrfToken::class])->name('editUser-api3');

Route::get('/searchTicket',[TicketController::class, 'searchTicket'])->name('searchTicket-api5');
Route::get('/getCityTicket',[TicketController::class, 'getCityTicket'])->name('getCityTicket-api4');
Route::get('/getdetailTicket',[TicketController::class, 'getdetailTicket'])->name('getdetailTicket-api6');

Route::post('/reserveTicket',[TicketController::class, 'reserveTicket'])->withoutMiddleware([VerifyCsrfToken::class])->name('reserveTicket-api7');

Route::post('/createPayment',[PaymentController::class, 'createPayment'])->withoutMiddleware([VerifyCsrfToken::class])->name('createPayment-api8');

Route::post('/showCancelValue',[TicketController::class, 'showCancelValue'])->withoutMiddleware([VerifyCsrfToken::class])->name('showCancelValue-api9');
Route::get('/getTicketUserBooking',[TicketController::class, 'getTicketUserBooking'])->name('getTicketUserBooking-api11');

Route::post('/cancelTicket',[TicketController::class, 'cancelTicket'])->withoutMiddleware([VerifyCsrfToken::class])->name('cancelTicket-api12');
Route::post('/createReport',[ReportController::class, 'createReport'])->withoutMiddleware(VerifyCsrfToken::class)->name('createReport-api13');

Route::get('/adminTicketManagement)',[TicketController::class, 'adminTicketManagement'])->name('adminTicketManagement-api10');
