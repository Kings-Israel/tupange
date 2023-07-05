<?php

use App\Jobs\SendSms;
use App\Mail\SendDispute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\EventProgramController;
use App\Http\Controllers\MpesaPaymentController;
use App\Http\Controllers\PesapalPaymentController;

Route::post('/stk-push-callback', [MpesaPaymentController::class, 'stkPushCallback'])->name('stk.push.callback');
Route::post('/order/iveri/success/callback', [CheckoutController::class, 'iveriSuccessCallback'])->name('orders.iveri.success.callback');
Route::post('/order/iveri/fail/callback', [CheckoutController::class, 'iveriFailedCallback'])->name('orders.iveri.fail.callback');

Route::post('/ticket/payment/mpesa/callback', [EventController::class, 'ticketPaymentMpesaCallback'])->name('ticket.stk.push.callback');

Route::post('/ticket/iveri/success/callback', [EventController::class, 'ticketPaymentIveriSuccessCallback'])->name('ticket.iveri.success.callback');
Route::post('/ticket/iveri/fail/callback', [EventController::class, 'ticketPaymentIveriFailedCallback'])->name('ticket.iveri.fail.callback');

Route::post('/pesapal/order/payment/notification', [PesapalPaymentController::class, 'orderPaymenntNotification'])->name('pesapal.order.payment.notification');
Route::post('/pesapal/program/payment/notification', [PesapalPaymentController::class, 'programPaymenntNotification'])->name('pesapal.program.payment.notification');
Route::post('/pesapal/ticket/payment/notification', [PesapalPaymentController::class, 'ticketOaymenntNotification'])->name('pesapal.ticket.payment.notification');

Route::get('/pesapal/order/payment/success/callback', [PesapalPaymentController::class, 'orderPaymentSuccessCallback'])->name('pesapal.order.payment.success.callback');
Route::get('/pesapal/order/payment/failed/callback', [PesapalPaymentController::class, 'orderPaymentFailedCallback'])->name('pesapal.order.payment.failed.callback');

Route::get('/pesapal/program/payment/success/callback', [PesapalPaymentController::class, 'programPaymentSuccessCallback'])->name('pesapal.program.payment.success.callback');
Route::get('/pesapal/program/payment/failed/callback', [PesapalPaymentController::class, 'programPaymentFailedCallback'])->name('pesapal.program.payment.failed.callback');

Route::get('/pesapal/ticket/payment/success/callback', [PesapalPaymentController::class, 'ticketPaymentSuccessCallback'])->name('pesapal.ticket.payment.success.callback');
Route::get('/pesapal/ticket/payment/failed/callback', [PesapalPaymentController::class, 'ticketPaymentFailedCallback'])->name('pesapal.ticket.payment.failed.callback');

Route::get('/pesapal/registered/ipns', [PesapalPaymentController::class, 'getRegisteredIpns']);
Route::get('/pesapal/register/order-payment/ipn', [PesapalPaymentController::class, 'registerOrderPaymentIpn']);
Route::get('/pesapal/register/ticket-payment/ipn', [PesapalPaymentController::class, 'registerTicketPaymentIpn']);
Route::get('/pesapal/register/program-payment/ipn', [PesapalPaymentController::class, 'registerEventProgramPaymentIpn']);

// DO NOT DELETE, USED IN PRISK CALLBACKS
Route::post('/transaction/confirmation', [MpesaPaymentController::class, 'confirmationCallback']);
Route::post('/transaction/validation', [MpesaPaymentController::class, 'validationCallback']);

Route::post('sms/test', function (Request $request) {
   SendSms::dispatch($request->phone_number, $request->content);
});

Route::post('/email/test', function (Request $request) {
   Mail::to($request->email)->send(new SendDispute($request->name, $request->email, $request->content));
});
