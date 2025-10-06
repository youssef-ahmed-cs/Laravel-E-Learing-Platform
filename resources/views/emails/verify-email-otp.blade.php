<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>OTP</title>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">
<div class="bg-white rounded-xl shadow-lg p-8 max-w-md w-full">
    <h1 class="text-2xl font-bold text-center text-gray-900 mb-4">Your OTP Code {{$user->two_factor_code}}</h1>
    <p class="text-center text-gray-700 mb-6">
        Your verification code is:
    </p>
    <div class="flex justify-center mb-6">
            <span
                class="text-3xl font-mono font-semibold tracking-widest bg-gray-100 px-6 py-3 rounded-lg border border-gray-200 text-blue-600">
                {{ $user->two_factor_code }}
            </span>
    </div>
    <p class="text-center text-gray-500 text-sm">
        This code is valid for the next <span class="font-medium text-gray-700">10 minutes</span>.<br>
        <span class="text-red-500 font-semibold">Do not share this code with anyone.</span>
    </p>
</div>
</body>
</html>
