<?php

use App\Enums\User;
use App\Http\Controllers\auth\UserController;
use App\Http\Controllers\LearnHttpController;
use App\Http\Controllers\UploadFromUrlController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Models\User as UserModel;

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
        'lang' => 'PHP',
        'framework' => 'Laravel',
        'version' => '12.x'
    ]);
});

Route::any('/data-app', static function () {
    return response()->json([
        'lang' => 'PHP'
    ]);
});

Route::get('/response', static function () {
    $arr = ['name' => 'Laravel', 'version' => '12.x'];
    return dump($arr);
});

Route::get('/exception', static function () {
    return throw new Exception('This is an error example');
});

Route::get('/set-session', static function () {
    Session::put('test', 'i am test value from session');
});

//Route::get('/get-session', static function () {
////    dd(Session::missing('test'));
//    if (Session::exists('test')) {
//        return response()->json(Session::get('test'));
//    }
//});

Route::get('/forget-session', static function () {
    Session::flush();
    return response()->json(['message' => 'Session value forgotten']);
});

Route::get('try', static fn() => response()->json(['GPA' => '3.60', 'department' => 'CS'], 200))
    ->name('try');

Route::get('users', [UserController::class, 'to_view'])->name('users');

Route::fallback(static function () {
    Log::debug('Fallback route triggered: ' . request()->url());
    return response()->json(['message' => 'Page not found'], 404);
});

Route::get('old-route', static function () {
    return collect(['name' => 'Quesadilla', 'role' => 'Developer'])->toPrettyJson();
});

Route::get('mask-string', static function () {
    dd(session('status'));
})->name('tryRoute');

Route::redirect('telescope', 'http://127.0.0.1:8000/telescope', 301);
# redirect to external URL code status 301 , 302 , 303 , 307 , 308

Route::get('http-client', LearnHttpController::class);

Route::get('/log', static function () {
    Log::emergency('Emergency level log');# system is unusable
    Log::alert('Alert level log'); # action must be taken immediately
    Log::critical('Critical level log'); # severe error
    Log::error('Error level log'); # runtime errors
    Log::warning('Warning level log'); # exceptional occurrences
    Log::notice('Notice level log'); # normal but significant condition
    Log::info('Info level log'); # interesting events
    Log::debug('Debug level log'); # detailed debug information
    return response()->json(['message' => 'Logs have been recorded. Check your log files.']);
});

Route::get('/url/{id}', static function () {
    return response()->json([
        'url' => request()->url(),
        'full_url' => request()->fullUrl(),
        'previous_url' => url()->previous(),
        'current_url' => url()->current(),
        'with query string ' => request()->fullUrlWithQuery([
            'name' => 'Laravel',
            'version' => '12.x'
        ]),
        'without query string ' => request()->fullUrlWithoutQuery([
            'name'
        ]),
        'route is ' => request()->routeIs('try'),
        'route uri ' => request()->route('id'),
    ]);
})->name('try');

Route::get('/person', UploadFromUrlController::class);
