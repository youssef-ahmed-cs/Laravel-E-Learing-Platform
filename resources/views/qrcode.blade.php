<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code</title>
</head>
<body class="min-h-screen flex flex-col items-center justify-center bg-gray-50 dark:bg-gray-900">
<h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-100">Your QR Code</h1>
<div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow flex items-center justify-center">
    {!! $qrCode !!}
</div>
</body>
</html>
