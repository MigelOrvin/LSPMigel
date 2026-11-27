<?php

namespace App\Services;

use App\Models\Doctor;
use App\Models\Schedule;
use App\Models\Reservation;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ReservationService
{
    private const TIMEZONE = 'Asia/Jakarta';

    private const SLOT_DURATION = 60;
    private const BOOKING_WINDOW = 30;

    private const BOOKED_STATUSES = [
        'pending',
        'approved',
        'confirmed',
        'completed'
    ];

    // Mengambil jadwal berdasarkan hari dari tanggal pilihan
    public function getSchedule(
        Doctor $doctor,
        string $date
    ): ?Schedule {

        $day = CarbonImmutable::parse(
            $date,
            self::TIMEZONE
        )->dayOfWeekIso;

        return $doctor->schedules()
            ->where('day_of_week', $day)
            ->first();
    }
    public function getAvailableDates(Doctor $doctor): array
    {
        if (!$doctor->is_active) {
            return [];
        }

        $today = CarbonImmutable::today(self::TIMEZONE);

        $practiceDays = $doctor->schedules()
            ->pluck('day_of_week')
            ->map(fn ($day) => (int) $day)
            ->toArray();

        $dates = [];

        for ($i = 0; $i < self::BOOKING_WINDOW; $i++) {

            $date = $today->addDays($i);

            // Lewati tanggal ketika dokter tidak praktik
            if (!in_array($date->dayOfWeekIso, $practiceDays, true)) {
                continue;
            }

            $slots = $this->getAvailableSlots(
                $doctor,
                $date->toDateString()
            );

            // Lewati jadwal yang tidak menghasilkan slot
            if (empty($slots)) {
                continue;
            }

            $availableCount = count(
                array_filter($slots, fn ($slot) => $slot['available'])
            );

            $dates[] = [
                'date' => $date->toDateString(),

                'day' => $date->locale('id')->translatedFormat('D'),

                'day_number' => $date->format('d'),

                'month' => $date->locale('id')->translatedFormat('M'),

                'available_count' => $availableCount,

                'total_slots' => count($slots),

                'available' => $availableCount > 0
            ];
        }

        return $dates;
    }
    // Membagi rentang jam praktik menjadi slot 60 menit
    private function generateSlots(
        Schedule $schedule,
        string $date
    ): array {

        $start = CarbonImmutable::parse(
            $date . ' ' . $schedule->start_time,
            self::TIMEZONE
        );

        $end = CarbonImmutable::parse(
            $date . ' ' . $schedule->end_time,
            self::TIMEZONE
        );

        $slots = [];

        while ($start->addMinutes(self::SLOT_DURATION)->lte($end)) {

            $slotEnd = $start->addMinutes(self::SLOT_DURATION);

            $slots[] = [
                'start_time' => $start->format('H:i:s'),
                'end_time' => $slotEnd->format('H:i:s')
            ];

            $start = $slotEnd;
        }

        return $slots;
    }

    // Mengambil slot yang tersedia dan sudah dipesan
    public function getAvailableSlots(
        Doctor $doctor,
        string $date
    ): array {

        if (!$doctor->is_active) {
            return [];
        }

        $schedule = $this->getSchedule($doctor, $date);

        if (!$schedule) {
            return [];
        }

        $reservations = Reservation::where(
                'schedule_id',
                $schedule->id
            )
            ->where('reservation_date', $date)
            ->whereIn('status', self::BOOKED_STATUSES)
            ->get(['start_time', 'end_time']);

        $slots = $this->generateSlots($schedule, $date);

        $now = CarbonImmutable::now(self::TIMEZONE);

        foreach ($slots as &$slot) {

            $isBooked = $reservations->contains(
                function ($reservation) use ($slot) {

                    return $reservation->start_time < $slot['end_time']
                        && $reservation->end_time > $slot['start_time'];
                }
            );

            $slotStart = CarbonImmutable::parse(
                $date . ' ' . $slot['start_time'],
                self::TIMEZONE
            );

            $isPast = $slotStart->lte($now);

            $slot['available'] = !$isBooked && !$isPast;

            $slot['status'] = match (true) {
                $isPast => 'expired',
                $isBooked => 'booked',
                default => 'available'
            };
        }

        unset($slot);

        return $slots;
    }

    // Membuat reservasi dengan database locking
    public function createReservation(
        User $user,
        Doctor $doctor,
        string $date,
        string $startTime,
        string $complaint
    ): Reservation {

        return DB::transaction(function () use (
            $user,
            $doctor,
            $date,
            $startTime,
            $complaint
        ) {

            // Lock data dokter agar status tidak berubah
            // ketika reservasi sedang diproses
            $doctor = Doctor::whereKey($doctor->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (!$doctor->is_active) {
                throw ValidationException::withMessages([
                    'doctor' => 'Dokter sedang tidak aktif.'
                ]);
            }

            $day = CarbonImmutable::parse(
                $date,
                self::TIMEZONE
            )->dayOfWeekIso;

            // Lock record jadwal
            $schedule = Schedule::where(
                    'doctor_id',
                    $doctor->id
                )
                ->where('day_of_week', $day)
                ->lockForUpdate()
                ->first();

            if (!$schedule) {
                throw ValidationException::withMessages([
                    'reservation_date' =>
                        'Dokter tidak praktik pada hari tersebut.'
                ]);
            }

            // Normalisasi jam
            $start = CarbonImmutable::parse(
                $date . ' ' . $startTime,
                self::TIMEZONE
            );

            $end = $start->addMinutes(self::SLOT_DURATION);

            if ($start->lte(
                CarbonImmutable::now(self::TIMEZONE)
            )) {
                throw ValidationException::withMessages([
                    'start_time' =>
                        'Waktu pemeriksaan sudah berlalu.'
                ]);
            }

            $normalizedStart = $start->format('H:i:s');
            $normalizedEnd = $end->format('H:i:s');

            // Pastikan jam berasal dari slot yang valid
            $validSlots = collect(
                $this->generateSlots($schedule, $date)
            );

            $isValidSlot = $validSlots->contains(
                'start_time',
                $normalizedStart
            );

            if (!$isValidSlot) {
                throw ValidationException::withMessages([
                    'start_time' => 'Slot pemeriksaan tidak valid.'
                ]);
            }

            // Time Interval Overlap
            $conflict = Reservation::where(
                    'schedule_id',
                    $schedule->id
                )
                ->where('reservation_date', $date)
                ->whereIn('status', self::BOOKED_STATUSES)
                ->where('start_time', '<', $normalizedEnd)
                ->where('end_time', '>', $normalizedStart)
                ->lockForUpdate()
                ->first();

            if ($conflict) {
                throw ValidationException::withMessages([
                    'start_time' =>
                        'Slot sudah dipesan. Silakan pilih jam lain.'
                ]);
            }

            // Simpan reservasi
            return Reservation::create([
                'reservation_code' =>
                    'RES-' . strtoupper((string) Str::ulid()),

                'user_id' => $user->id,

                'schedule_id' => $schedule->id,

                'reservation_date' => $date,

                'start_time' => $normalizedStart,

                'end_time' => $normalizedEnd,

                'complaint' => $complaint,

                'total_amount' => $doctor->consultation_fee,

                'status' => 'pending'
            ]);
        }, 3);
    }
}
