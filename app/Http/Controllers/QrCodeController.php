<?php

namespace App\Http\Controllers;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeController extends Controller
{
    public function __invoke()
    {
        $logoPath = public_path('logo.png');

        if (! file_exists($logoPath)) {
            abort(404, 'Logo file not found.');
        }

        $qrCode = QrCode::size(250)
            ->format('png')
            ->generate('youssef.ahmed.fci@gmail.com');

        return response($qrCode)->header('Content-Type', 'image/png');
    }
}
