<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Reservation;
use App\Services\ReservationService;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use App\Models\Announcement;
class DashboardController extends Controller
{
    public function __construct(
        private ReservationService $reservationService
    ) {}

    public function index(Request $request)
    {
        $today = CarbonImmutable::today('Asia/Jakarta');

        // Dokter aktif beserta jadwal praktik
        $doctors = Doctor::with([
            'schedules' => function ($query) {
                $query->orderBy('day_of_week');
            }
        ])
        ->where('is_active', true)
        ->orderBy('name')
        ->get();

        // Kalender tujuh hari ke depan
        $calendarDates = [];

        for ($i = 0; $i < 7; $i++) {

            $date = $today->addDays($i);

            $calendarDates[] = [
                'date' => $date->format('Y-m-d'),
                'day' => $date->locale('id')->translatedFormat('D'),
                'label' => $date->locale('id')->translatedFormat('d M'),
                'day_of_week' => $date->dayOfWeekIso,
                'is_today' => $i === 0
            ];
        }

        // Ketersediaan slot setiap dokter
        $doctorAvailability = [];

        foreach ($doctors as $doctor) {

            foreach ($calendarDates as $date) {

                $schedule = $doctor->schedules
                    ->firstWhere(
                        'day_of_week',
                        $date['day_of_week']
                    );

                if (!$schedule) {

                    $doctorAvailability[$doctor->id][$date['date']] = [
                        'status' => 'closed',
                        'available_count' => 0
                    ];

                    continue;
                }

                $slots = $this->reservationService
                    ->getAvailableSlots(
                        $doctor,
                        $date['date']
                    );

                $availableCount = collect($slots)
                    ->where('available', true)
                    ->count();

                $doctorAvailability[$doctor->id][$date['date']] = [
                    'status' => $availableCount > 0
                        ? 'available'
                        : 'unavailable',

                    'start_time' => substr(
                        $schedule->start_time,
                        0,
                        5
                    ),

                    'end_time' => substr(
                        $schedule->end_time,
                        0,
                        5
                    ),

                    'available_count' => $availableCount
                ];
            }
        }

        // Reservasi aktif milik pasien
        $upcomingReservations = Reservation::with([
            'schedule.doctor',
            'payment'
        ])
        ->where('user_id', $request->user()->id)
        ->active()
        ->orderBy('reservation_date')
        ->orderBy('start_time')
        ->limit(3)
        ->get();

        $latestAnnouncements = Announcement::published()
        ->orderByDesc('published_at')
        ->orderByDesc('id')
        ->limit(3)
        ->get();

        return view('patient.dashboard', compact(
            'doctors',
            'calendarDates',
            'doctorAvailability',
            'upcomingReservations',
            'latestAnnouncements'
        ));
    }
}
