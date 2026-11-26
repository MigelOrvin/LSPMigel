<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => 'nullable|string|max:255',
            'status' => 'nullable|in:all,pending,approved,rejected'
        ]);

        $search = trim($validated['search'] ?? '');
        $status = $validated['status'] ?? 'all';

        // Base query: hanya akun pasien
        $query = User::where('role', 'patient');

        // Statistik pasien
        $totalPatients = (clone $query)->count();

        $pendingPatients = (clone $query)
            ->where('status', 'pending')
            ->count();

        $approvedPatients = (clone $query)
            ->where('status', 'approved')
            ->count();

        $rejectedPatients = (clone $query)
            ->where('status', 'rejected')
            ->count();

        // Pencarian dan filter
        $patients = $query
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });
            })
            ->when($status !== 'all', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderByRaw(
                "CASE WHEN status = 'pending' THEN 0 ELSE 1 END"
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.patients.index', compact(
            'patients',
            'search',
            'status',
            'totalPatients',
            'pendingPatients',
            'approvedPatients',
            'rejectedPatients'
        ));
    }

    public function updateStatus(Request $request, User $patient)
    {
        abort_unless($patient->role === 'patient', 404);

        $validated = $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);

        $previousStatus = $patient->status;
        $newStatus = $validated['status'];

        if ($previousStatus === $newStatus) {
            return back()->withErrors([
                'status' => 'Status pasien sudah sesuai.'
            ]);
        }

        $updated = User::whereKey($patient->id)
            ->where('role', 'patient')
            ->where('status', $previousStatus)
            ->update([
                'status' => $newStatus
            ]);

        if (!$updated) {
            return back()->withErrors([
                'status' => 'Status pasien telah berubah. Silakan coba kembali.'
            ]);
        }

        $message = match (true) {
            $previousStatus === 'rejected' && $newStatus === 'approved'
                => 'Akses pasien berhasil dipulihkan.',

            $previousStatus === 'approved' && $newStatus === 'rejected'
                => 'Akses pasien berhasil dicabut.',

            $newStatus === 'approved'
                => 'Pendaftaran pasien berhasil disetujui.',

            default
                => 'Pendaftaran pasien berhasil ditolak.'
        };

        return back()->with('success', $message);
    }
}
