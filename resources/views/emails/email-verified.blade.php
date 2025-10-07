<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email Verified Successfully</title>
    @vite('resources/css/app.css')
</head>
<body
    class="bg-gray-50 text-gray-900 dark:bg-gray-900 dark:text-gray-100 min-h-screen flex items-center justify-center">
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8 max-w-md w-full text-center">
    <svg class="mx-auto mb-4 h-16 w-16 text-green-500" fill="none" stroke="currentColor" stroke-width="2"
         viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"/>
        <path d="M8 12l2 2l4-4" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"
              stroke-linejoin="round"/>
    </svg>
    <h1 class="text-2xl font-bold mb-2">Hi {{$user->name}} Your email has been verified!</h1>
    <p class="mb-4">Thank you for verifying your email address. You can now access all the features of our platform.</p>
    <p class="mb-6">If you have any questions or need assistance, feel free to contact our support team.</p>
    <p class="text-sm text-gray-500 dark:text-gray-400">Best regards,<br>The Team</p>
</div>
</body>
</html>
