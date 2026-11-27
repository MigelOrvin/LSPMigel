<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Reservation;
use App\Models\Schedule;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use RuntimeException;
use Throwable;

class PaymentService
{
    public function ensurePayable(
        Reservation $reservation,
        User $user
    ): void {

        // Pastikan reservasi milik pasien
        abort_unless(
            $reservation->user_id === $user->id,
            403
        );

        if ($reservation->status !== 'pending') {

            throw ValidationException::withMessages([
                'payment' => 'Reservasi tidak dapat dibayar.'
            ]);
        }

        $appointmentTime = CarbonImmutable::parse(
            $reservation->reservation_date->format('Y-m-d')
                . ' '
                . $reservation->start_time,
            'Asia/Jakarta'
        );

        if ($appointmentTime->lte(
            CarbonImmutable::now('Asia/Jakarta')
        )) {

            throw ValidationException::withMessages([
                'payment' => 'Waktu pemeriksaan telah berlalu.'
            ]);
        }

        if (in_array(
            $reservation->payment?->status,
            ['pending', 'approved'],
            true
        )) {

            throw ValidationException::withMessages([
                'payment' => 'Pembayaran sudah diajukan sebelumnya.'
            ]);
        }
    }

    public function submitPayment(
        User $user,
        Reservation $reservation,
        UploadedFile $proof
    ): Payment {

        $this->ensurePayable($reservation, $user);

        // Simpan bukti pembayaran secara private
        $path = $proof->store(
            'payment-proofs',
            'local'
        );

        if (!$path) {
            throw new RuntimeException(
                'Gagal menyimpan bukti pembayaran.'
            );
        }

        try {

            [$payment, $oldProof] = DB::transaction(
                function () use (
                    $user,
                    $reservation,
                    $path
                ) {

                    // Lock jadwal
                    Schedule::whereKey(
                        $reservation->schedule_id
                    )
                    ->lockForUpdate()
                    ->firstOrFail();

                    // Lock reservasi
                    $reservation = Reservation::whereKey(
                        $reservation->id
                    )
                    ->lockForUpdate()
                    ->firstOrFail();

                    // Lock pembayaran jika tersedia
                    $existingPayment = $reservation->payment()
                        ->lockForUpdate()
                        ->first();

                    $reservation->setRelation(
                        'payment',
                        $existingPayment
                    );

                    // Validasi ulang setelah locking
                    $this->ensurePayable(
                        $reservation,
                        $user
                    );

                    $oldProof = $existingPayment?->payment_proof;

                    $payment = $reservation->payment()
                        ->updateOrCreate(
                            [],
                            [
                                'amount' => $reservation->total_amount,
                                'payment_proof' => $path,
                                'status' => 'pending',
                                'admin_note' => null,
                                'verified_by' => null,
                                'verified_at' => null
                            ]
                        );

                    return [$payment, $oldProof];

                },
                3
            );

        } catch (Throwable $exception) {

            Storage::disk('local')->delete($path);

            throw $exception;
        }

        // Hapus bukti lama setelah transaksi berhasil
        if ($oldProof && $oldProof !== $path) {

            Storage::disk('local')->delete($oldProof);
        }

        return $payment;
    }
}
