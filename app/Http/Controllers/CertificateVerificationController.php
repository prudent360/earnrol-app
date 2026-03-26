<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Setting;

class CertificateVerificationController extends Controller
{
    public function verify($certificateNumber)
    {
        $certificate = Certificate::where('certificate_number', $certificateNumber)
            ->with(['user', 'cohort'])
            ->first();

        $appName = Setting::get('app_name', 'EarnRol');

        return view('certificates.verify', compact('certificate', 'appName'));
    }
}
