<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FeeController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;
        $fees = $student->fees()->with('payments')->latest()->paginate(15);

        return view('student.fees-index', compact('fees'));
    }

    public function show($feeId)
    {
        $student = auth()->user()->student;
        $fee = $student->fees()->with('payments')->findOrFail($feeId);

        return view('student.fee-details', compact('fee'));
    }
}
