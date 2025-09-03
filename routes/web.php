<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', static function () {
    return view('welcome');
})->name('home');

Route::get('/user/name', static function (Request $request) {
    return "User of name " . $request->get('name');
});

Route::get('/user/redirect', static function () {
    return to_route('home');
});

Route::view('/layout', 'layout')->name('layout');
