<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/checkout-page', function () {
    return view('checkout');
});

Route::get('/success', function () {
    return view('success');
});

Route::get('/failed', function () {
    return view('failed');
});


Route::get('/cancelled', function () {
    return view('cancelled');
});
