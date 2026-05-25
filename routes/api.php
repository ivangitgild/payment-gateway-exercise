<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Middleware\Cors;
use App\Http\Middleware\ForceJsonResponse;
use App\Http\Middleware\PaymongoWebhook;
use Illuminate\Http\Request;

Route::middleware([ForceJsonResponse::class, Cors::class])->group(function () {
    Route::middleware([PaymongoWebhook::class])->post('webhook', function (Request $request) {
        logger()->info($request->all());
    });

    Route::post('checkout', [CheckoutController::class, 'store']);

});

