<?php

namespace App\Console\Commands;

use App\Models\Reservation;
use App\Models\Schedule;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExpireReservations extends Command
{
    protected $signature = 'reservations:expire';

    protected $description =
        'Expire unpaid reservations after the payment deadline';

    public function handle(): int
    {
        $expiredCount = 0;

        Reservation::query()
            ->expiredUnpaid()
            ->select(['id', 'schedule_id'])
            ->chunkById(100, function ($reservations) use (
                &$expiredCount
            ) {

                foreach ($reservations as $candidate) {

                    $expired = DB::transaction(
                        function () use ($candidate) {

                            // Lock jadwal terlebih dahulu
                            $schedule = Schedule::whereKey(
                                $candidate->schedule_id
                            )
                            ->lockForUpdate()
                            ->first();

                            if (!$schedule) {
                                return false;
                            }

                            // Lock reservasi
                            $reservation = Reservation::whereKey(
                                $candidate->id
                            )
                            ->lockForUpdate()
                            ->first();

                            if (!$reservation) {
                                return false;
                            }

                            // Lock pembayaran jika tersedia
                            $payment = $reservation->payment()
                                ->lockForUpdate()
                                ->first();

                            $reservation->setRelation(
                                'payment',
                                $payment
                            );

                            // Validasi ulang setelah locking
                            if (!$reservation->hasPaymentExpired()) {
                                return false;
                            }

                            $reservation->update([
                                'status' => 'expired'
                            ]);

                            return true;

                        },
                        3
                    );

                    if ($expired) {
                        $expiredCount++;
                    }
                }
            });

        $this->info(
            "{$expiredCount} reservasi berhasil dikedaluwarsakan."
        );

        return self::SUCCESS;
    }
}
