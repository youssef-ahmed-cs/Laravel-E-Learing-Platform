<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Enums\UserRols;
use App\Providers\MagicalMacroServiceProvider;
// Log all database queries for debugging
//DB::listen(function ($query) {
//    Log::info('Query Executed', [
//        'sql' => $query->sql,
//        'bindings' => $query->bindings,
//        'time' => $query->time,
//    ]);
//});

//$user = User::query()->select('id', 'name', 'role')
//    ->where('id', 2)
//    ->first();
//DB::enableQueryLog();
//$users = User::latest()->take(5)->get(['id', 'name', 'role']);
//$queries = DB::getQueryLog();
//$user = User::factory()->create();
//$user->name;

$user = User::find(200);
$user->isAdmin();
