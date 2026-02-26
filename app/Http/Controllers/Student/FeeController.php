<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FeeController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;
        $fees = $student->fees()->with('payments')->latest()->get();

        $stats = [
            'total_due' => $fees->sum('total_amount'),
            'total_paid' => $fees->sum('paid_amount'),
            'total_remaining' => $fees->sum('total_amount') - $fees->sum('paid_amount'),
            'payment_progress' => $fees->sum('total_amount') > 0 ? ($fees->sum('paid_amount') / $fees->sum('total_amount')) * 100 : 0
        ];

        return view('student.fees-index', compact('fees', 'stats'));
    }

    public function show($feeId)
    {
        $student = auth()->user()->student;
        $fee = $student->fees()->with('payments')->findOrFail($feeId);

        return view('student.fee-details', compact('fee'));
    }
}
