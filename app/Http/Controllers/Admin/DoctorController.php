<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $doctors = Doctor::with([
            'schedules' => function ($query) {
                $query->orderBy('day_of_week');
            }
        ])
        ->when($search, function ($query, $search) {
            $query->where('name', 'like', '%' . $search . '%');
        })
        ->orderByDesc('is_active')
        ->orderBy('name')
        ->get();

        return view('admin.doctors.index', compact(
            'doctors',
            'search'
        ));
    }
    public function updateStatus(Request $request, Doctor $doctor)
    {
        $validated = $request->validate([
            'is_active' => ['required', 'boolean']
        ]);

        $doctor->update([
            'is_active' => $validated['is_active']
        ]);

        $message = $doctor->is_active
            ? 'Dokter berhasil diaktifkan.'
            : 'Dokter berhasil dinonaktifkan.';

        return back()->with('success', $message);
    }
}