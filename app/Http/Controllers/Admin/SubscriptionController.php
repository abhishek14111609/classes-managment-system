<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolSubscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = SchoolSubscription::with(['school', 'plan'])
            ->latest()
            ->paginate(20);

        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    public function download(SchoolSubscription $subscription)
    {
        $subscription->load(['school', 'plan']);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.subscriptions.invoice_pdf', compact('subscription'));

        return $pdf->download("Invoice-{$subscription->invoice_number}.pdf");
    }
}
