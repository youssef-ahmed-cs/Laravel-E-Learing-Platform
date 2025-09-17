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

Route::any('/data', static function () {
    return response()->json([
        'lang' => 'PHP'
    ]);
});
Route::any('/data-app', static function () {
    return response()->json([
        'lang' => 'PHP'
    ]);
});

Route::get('/set-session', static function () {
    Session::put('test', 'i am test value from session');
});

Route::get('/get-session', static function () {
//    dd(Session::missing('test'));
    if(Session::exists('test')) {
        return response()->json(Session::get('test'));
    }
});

Route::get('/forget-session', static function () {
    Session::flush();
    return response()->json(['message' => 'Session value forgotten']);
});

Route::get('try', static fn() => response()->json(['GPA' => '3.60', 'department' => 'CS'], 200))
->name('try');
