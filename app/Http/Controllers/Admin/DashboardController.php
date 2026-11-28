<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Reservation;
use App\Models\Payment;
use App\Models\Announcement;
use Carbon\CarbonImmutable;

class DashboardController extends Controller
{
    public function index()
    {
        $now = CarbonImmutable::now('Asia/Jakarta');

        $today = $now->toDateString();

        /* ========================================
           OVERVIEW STATISTICS
        ======================================== */

        $approvedPatients = User::where('role', 'patient')
            ->where('status', 'approved')
            ->count();

        $activeDoctors = Doctor::where('is_active', true)
            ->count();

        $todayAppointments = Reservation::where(
            'reservation_date', $today
        )
        ->whereIn('status', [
            'approved',
            'confirmed',
            'completed'
        ])
        ->count();

        $totalVerifiedPayments = Payment::where(
            'status', 'approved'
        )->sum('amount');

        /* ========================================
           ACTION REQUIRED
        ======================================== */

        $pendingPatients = User::where('role', 'patient')
            ->where('status', 'pending')
            ->count();

        $pendingPayments = Payment::where(
            'status', 'pending'
        )->count();

        $pendingReservations = Reservation::where(
            'status', 'pending'
        )
        ->whereHas('payment', function ($query) {
            $query->where('status', 'approved');
        })
        ->count();

        $totalPendingActions = $pendingPatients
            + $pendingPayments
            + $pendingReservations;

        /* ========================================
           RESERVATION ANALYTICS
        ======================================== */

        $chartStart = $now->startOfDay()->subDays(6);

        $dailyReservations = Reservation::query()
            ->where(
                'created_at',
                '>=',
                $chartStart->setTimezone(config('app.timezone'))
            )
            ->where(
                'created_at',
                '<=',
                $now->setTimezone(config('app.timezone'))
            )
            ->get(['created_at']);

        $groupedReservations = $dailyReservations
            ->groupBy(function ($reservation) {

                return $reservation->created_at
                    ->timezone('Asia/Jakarta')
                    ->toDateString();

            });

        $reservationChart = [];

        for ($i = 0; $i < 7; $i++) {

            $date = $chartStart->addDays($i);

            $dateKey = $date->toDateString();

            $reservationChart[] = [
                'label' => $date->locale('id')
                    ->translatedFormat('d M'),

                'day' => $date->locale('id')
                    ->translatedFormat('D'),

                'total' => $groupedReservations
                    ->get($dateKey, collect())
                    ->count(),

                'is_today' => $dateKey === $today
            ];
        }

        $chartMax = max(
            4,
            (int) ceil(
                collect($reservationChart)->max('total') / 4
            ) * 4
        );

        $weeklyReservations = collect(
            $reservationChart
        )->sum('total');

        /* ========================================
           TODAY APPOINTMENTS
        ======================================== */

        $appointments = Reservation::with([
            'user',
            'schedule.doctor'
        ])
        ->where('reservation_date', $today)
        ->whereIn('status', [
            'approved',
            'confirmed',
            'completed'
        ])
        ->orderBy('start_time')
        ->limit(8)
        ->get();

        /* ========================================
           DOCTORS PRACTICING TODAY
        ======================================== */

        $todayDoctors = Doctor::query()
            ->where('is_active', true)

            ->whereHas('schedules', function ($query) use ($now) {

                $query->where(
                    'day_of_week',
                    $now->dayOfWeekIso
                );

            })

            ->with([
                'schedules' => function ($query) use ($now) {

                    $query->where(
                        'day_of_week',
                        $now->dayOfWeekIso
                    );

                }
            ])

            ->orderBy('name')
            ->get();

        /* ========================================
           RECENT ANNOUNCEMENTS
        ======================================== */

        $latestAnnouncements = Announcement::published()
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->limit(3)
            ->get();

        return view('admin.dashboard', compact(
            'now',
            'approvedPatients',
            'activeDoctors',
            'todayAppointments',
            'totalVerifiedPayments',
            'pendingPatients',
            'pendingPayments',
            'pendingReservations',
            'totalPendingActions',
            'reservationChart',
            'chartMax',
            'weeklyReservations',
            'appointments',
            'todayDoctors',
            'latestAnnouncements'
        ));
    }
}
