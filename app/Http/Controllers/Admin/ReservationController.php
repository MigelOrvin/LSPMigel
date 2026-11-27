<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Reservation;
use App\Services\ReservationService;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function __construct(
        private ReservationService $reservationService
    ) {}

    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => 'nullable|string|max:255',
            'status' => 'nullable|in:all,pending,approved,rejected,confirmed,completed,cancelled,expired',
            'doctor_id' => 'nullable|integer|exists:doctors,id',
            'date' => 'nullable|date_format:Y-m-d'
        ]);

        $search = trim($validated['search'] ?? '');
        $status = $validated['status'] ?? 'all';
        $doctorId = $validated['doctor_id'] ?? null;
        $date = $validated['date'] ?? null;

        // Statistik keseluruhan
        $totalReservations = Reservation::count();

        $pendingReservations = Reservation::where(
            'status', 'pending'
        )
        ->whereHas('payment', function ($query) {

            $query->where('status', 'approved');

        })
        ->count();

        $approvedReservations = Reservation::whereIn(
            'status',
            ['approved', 'confirmed', 'completed']
        )->count();

        $rejectedReservations = Reservation::where(
            'status', 'rejected'
        )->count();

        // Data dokter untuk filter
        $doctors = Doctor::orderBy('name')->get();

        // Query reservasi
        $reservations = Reservation::with([
            'user',
            'schedule.doctor',
            'verifier',
            'payment.verifier'
        ])

        // Search
        ->when($search !== '', function ($query) use ($search) {

            $query->where(function ($query) use ($search) {

                $query->where(
                    'reservation_code',
                    'like',
                    '%' . $search . '%'
                )

                ->orWhereHas('user', function ($query) use ($search) {

                    $query->where(
                        'name',
                        'like',
                        '%' . $search . '%'
                    )->orWhere(
                        'email',
                        'like',
                        '%' . $search . '%'
                    );
                })

                ->orWhereHas(
                    'schedule.doctor',
                    function ($query) use ($search) {

                        $query->where(
                            'name',
                            'like',
                            '%' . $search . '%'
                        );
                    }
                );
            });
        })

        // Filter status
        ->when($status !== 'all', function ($query) use ($status) {

            if ($status === 'expired') {

                $query->where(function ($query) {

                    $query->where('status', 'expired')

                        ->orWhere(function ($query) {

                            $query->expiredUnpaid();

                        });
                });

            } elseif ($status === 'pending') {

                $query->where('status', 'pending')

                    ->whereHas('payment', function ($query) {

                        $query->where('status', 'approved');

                    });

            } else {

                $query->where('status', $status);

            }

        })

        // Filter dokter
        ->when($doctorId, function ($query) use ($doctorId) {

            $query->whereHas('schedule', function ($query) use ($doctorId) {

                $query->where('doctor_id', $doctorId);

            });
        })

        // Filter tanggal
        ->when($date, function ($query) use ($date) {

            $query->where('reservation_date', $date);

        })

        // Prioritaskan reservasi pending
        ->orderByRaw("
            CASE
                WHEN reservations.status = 'pending'
                    AND EXISTS (
                        SELECT 1
                        FROM payments
                        WHERE payments.reservation_id = reservations.id
                            AND payments.status = 'approved'
                    )
                THEN 0

                WHEN reservations.status = 'pending'
                THEN 1

                ELSE 2
            END
        ")

        ->orderBy('reservation_date')
        ->orderBy('start_time')
        ->orderByDesc('id')

        ->paginate(8)
        ->withQueryString();

        return view('admin.reservations.index', compact(
            'reservations',
            'doctors',
            'search',
            'status',
            'doctorId',
            'date',
            'totalReservations',
            'pendingReservations',
            'approvedReservations',
            'rejectedReservations'
        ));
    }

    public function updateStatus(
        Request $request,
        Reservation $reservation
    ) {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',

            'admin_note' => [
                'nullable',
                'required_if:status,rejected',
                'string',
                'max:1000'
            ]
        ]);

        $reservation = $this->reservationService
            ->verifyReservation(
                $reservation,
                $request->user(),
                $validated['status'],
                $validated['admin_note'] ?? null
            );

        $message = $reservation->status === 'approved'

            ? 'Reservasi berhasil disetujui.'

            : 'Reservasi berhasil ditolak. '
                . 'Slot kembali tersedia jika waktunya belum berlalu. '
                . 'Silakan proses pengembalian dana secara manual.';

        return back()->with('success', $message);
    }
}
