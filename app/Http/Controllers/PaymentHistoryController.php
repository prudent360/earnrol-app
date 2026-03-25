<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PaymentHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::where('user_id', Auth::id())
            ->with('payable')
            ->latest();

        if ($request->get('status')) {
            $query->where('status', $request->get('status'));
        }

        $payments = $query->paginate(15)->withQueryString();

        return view('payments.history', compact('payments'));
    }

    public function exportCsv()
    {
        $payments = Payment::where('user_id', Auth::id())
            ->with('payable')
            ->latest()
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=my-payments.csv',
        ];

        return new StreamedResponse(function () use ($payments) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Date', 'Cohort', 'Amount', 'Currency', 'Gateway', 'Status', 'Reference']);

            foreach ($payments as $payment) {
                fputcsv($handle, [
                    $payment->created_at->format('Y-m-d H:i'),
                    $payment->payable->title ?? 'N/A',
                    number_format($payment->amount, 2),
                    $payment->currency ?? 'GBP',
                    ucfirst($payment->gateway),
                    ucfirst($payment->status),
                    $payment->reference,
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }
}
