<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('front-page');
});
Route::get('/cart', function () {
    return view('cart');
});
Route::get('/about', function () {
    return view('about');
});
Route::get('/test', function () {
    return view('test');
});