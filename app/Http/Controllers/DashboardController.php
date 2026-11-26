<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPatients = User::where('role', 'patient')
            ->count();

        $pendingPatients = User::where('role', 'patient')
            ->where('status', 'pending')
            ->count();

        $approvedPatients = User::where('role', 'patient')
            ->where('status', 'approved')
            ->count();

        $rejectedPatients = User::where('role', 'patient')
            ->where('status', 'rejected')
            ->count();

        $recentPatients = User::where('role', 'patient')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalPatients',
            'pendingPatients',
            'approvedPatients',
            'rejectedPatients',
            'recentPatients'
        ));
    }
}