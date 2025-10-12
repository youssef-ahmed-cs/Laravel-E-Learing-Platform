<?php

namespace App\Http\Controllers;

use App\Models\User;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeController extends Controller
{

    public function generate()
    {
        $logoPath = public_path('logo.png');

        if (!file_exists($logoPath)) {
            abort(404, 'Logo file not found.');
        }

        $qrCode = QrCode::size(250)
            ->merge($logoPath, 0.3, true)
            ->errorCorrection('H')
            ->generate($user->email ?? 'default@example.com');

        return response($qrCode);
    }

}
