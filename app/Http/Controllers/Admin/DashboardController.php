<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SchoolService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(private SchoolService $schoolService) {}

    public function index()
    {
        $stats = $this->schoolService->getDashboardStats();

        return view('admin.dashboard', compact('stats'));
    }
}
