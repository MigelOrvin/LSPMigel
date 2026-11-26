<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Doctor;

class DashboardController extends Controller
{
    public function index()
    {
        $doctors = Doctor::with([
            'schedules' => function ($query) {
                $query->orderBy('day_of_week');
            }
        ])
        ->where('is_active', true)
        ->orderBy('name')
        ->get();

        return view('patient.dashboard', compact(
            'doctors'
        ));
    }
}