<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Reservation;
use App\Services\ReservationService;
use Illuminate\Http\Request;
use Carbon\CarbonImmutable;

class ReservationController extends Controller
{
    private const TIMEZONE = 'Asia/Jakarta';

    private const BOOKING_WINDOW = 30;

    public function __construct(
        private ReservationService $reservationService
    ) {}

    // Menampilkan riwayat reservasi pasien
    public function index(Request $request)
    {
        $reservations = Reservation::with([
            'schedule.doctor',
            'payment'
        ])
        ->where('user_id', $request->user()->id)
        ->latest()
        ->paginate(8);

        return view(
            'patient.reservations.index',
            compact('reservations')
        );
    }

    // Menampilkan form reservasi
    public function create(Doctor $doctor)
    {
        abort_unless($doctor->is_active, 404);

        $doctor->load([
            'schedules' => function ($query) {
                $query->orderBy('day_of_week');
            }
        ]);

        $availableDates = $this->reservationService
            ->getAvailableDates($doctor);

        return view(
            'patient.reservations.create',
            compact('doctor', 'availableDates')
        );
    }

    // Mengambil slot berdasarkan tanggal melalui AJAX
    public function slots(Request $request, Doctor $doctor)
    {
        abort_unless($doctor->is_active, 404);

        $validated = $request->validate([
            'date' => $this->dateRules()
        ]);

        $slots = $this->reservationService
            ->getAvailableSlots(
                $doctor,
                $validated['date']
            );

        return response()->json([
            'slots' => $slots
        ]);
    }

    // Menyimpan reservasi
    public function store(Request $request, Doctor $doctor)
    {
        $validated = $request->validate([
            'reservation_date' => $this->dateRules(),

            'start_time' => [
                'required',
                'date_format:H:i:s'
            ],

            'complaint' => [
                'required',
                'string',
                'max:1000'
            ]
        ]);

        $reservation = $this->reservationService
            ->createReservation(
                $request->user(),
                $doctor,
                $validated['reservation_date'],
                $validated['start_time'],
                $validated['complaint']
            );

        return redirect()
        ->route('patient.payments.create', $reservation)
        ->with(
            'success',
            'Reservasi berhasil diajukan. '
            . 'Silakan lanjutkan pembayaran.'
        );
    }

    // Validasi rentang tanggal reservasi
    private function dateRules(): array
    {
        $today = CarbonImmutable::today(self::TIMEZONE);

        $maxDate = $today->addDays(
            self::BOOKING_WINDOW - 1
        );

        return [
            'required',
            'date_format:Y-m-d',
            'after_or_equal:' . $today->toDateString(),
            'before_or_equal:' . $maxDate->toDateString()
        ];
    }
}
