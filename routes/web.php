<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/checkout-page', function () {
    return view('checkout');
});

Route::get('/success', function () {});

Route::get('/failed', function () {});
