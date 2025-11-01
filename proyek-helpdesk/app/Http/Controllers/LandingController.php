<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;

class LandingController extends Controller
{
    public function index()
    {
        $totalReports = Report::count();
        $pendingReports = Report::where('status', 'pending')->count();
        $completedReports = Report::whereIn('status', ['completed', 'rated'])->count();
        $rejectedReports = Report::where('status', 'rejected')->count();

        return view('landing', [
            'totalReports' => $totalReports,
            'pendingReports' => $pendingReports,
            'completedReports' => $completedReports,
            'rejectedReports' => $rejectedReports,
        ]);
    }
}
