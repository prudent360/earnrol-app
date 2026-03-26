<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateController extends Controller
{
    public function index()
    {
        $certificates = auth()->user()
            ->certificates()
            ->with('cohort')
            ->latest('issued_at')
            ->get();

        return view('certificates.index', compact('certificates'));
    }

    public function download(Certificate $certificate)
    {
        abort_if($certificate->user_id !== auth()->id(), 403);

        $pdf = Pdf::loadView('certificates.pdf', [
            'certificate' => $certificate,
            'user' => $certificate->user,
            'cohort' => $certificate->cohort,
            'appName' => Setting::get('app_name', 'EarnRol'),
            'verifyUrl' => route('certificates.verify', $certificate->certificate_number),
        ])->setPaper('a4', 'landscape');

        return $pdf->download("certificate-{$certificate->certificate_number}.pdf");
    }
}
