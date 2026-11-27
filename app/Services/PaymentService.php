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

        abort_unless(
            $reservation->user_id === $user->id,
            403
        );

        if (
            $reservation->status === 'expired' ||
            $reservation->hasPaymentExpired()
        ) {

            throw ValidationException::withMessages([
                'payment' =>
                    'Waktu pembayaran telah habis. '
                    . 'Silakan buat reservasi baru.'
            ]);
        }

        if ($reservation->status !== 'pending') {

            throw ValidationException::withMessages([
                'payment' =>
                    'Reservasi tidak dapat dibayar.'
            ]);
        }

        // Upload ulang tidak diperbolehkan lagi
        if ($reservation->payment) {

            throw ValidationException::withMessages([
                'payment' =>
                    'Pembayaran sudah pernah diajukan.'
            ]);
        }

        if (!$reservation->can_pay) {

            throw ValidationException::withMessages([
                'payment' =>
                    'Waktu pembayaran atau pemeriksaan '
                    . 'sudah tidak tersedia.'
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

                    $payment = $reservation->payment()->create([
                        'amount' => $reservation->total_amount,
                        'payment_proof' => $path,
                        'status' => 'pending',
                        'admin_note' => null,
                        'verified_by' => null,
                        'verified_at' => null
                    ]);

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

    public function verifyPayment(
        Payment $payment,
        User $admin,
        string $status,
        ?string $note = null
    ): Payment {

        abort_unless(
            $admin->role === 'admin' &&
            $admin->status === 'approved',
            403
        );

        if (!in_array($status, ['approved', 'rejected'], true)) {

            throw ValidationException::withMessages([
                'status' => 'Status pembayaran tidak valid.'
            ]);
        }

        if ($status === 'rejected' && !trim($note ?? '')) {

            throw ValidationException::withMessages([
                'admin_note' => 'Alasan penolakan wajib diisi.'
            ]);
        }

        return DB::transaction(function () use (
            $payment,
            $admin,
            $status,
            $note
        ) {

            $reservationId = $payment->reservation_id;

            $scheduleId = Reservation::whereKey($reservationId)
                ->value('schedule_id');

            // Lock dengan urutan yang konsisten
            Schedule::whereKey($scheduleId)
                ->lockForUpdate()
                ->firstOrFail();

            $reservation = Reservation::whereKey($reservationId)
                ->lockForUpdate()
                ->firstOrFail();

            $payment = Payment::whereKey($payment->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($payment->status !== 'pending') {

                throw ValidationException::withMessages([
                    'status' => 'Pembayaran sudah diverifikasi.'
                ]);
            }

            if ($reservation->status !== 'pending') {

                throw ValidationException::withMessages([
                    'status' => 'Reservasi tidak dapat diproses.'
                ]);
            }

            if (
                $status === 'approved' &&
                !Storage::disk('local')->exists(
                    $payment->payment_proof
                )
            ) {

                throw ValidationException::withMessages([
                    'payment' => 'File bukti pembayaran tidak ditemukan.'
                ]);
            }

            $payment->update([
                'status' => $status,
                'admin_note' => $note,
                'verified_by' => $admin->id,
                'verified_at' => now()
            ]);

            if ($status === 'rejected') {

                $reservation->update([
                    'status' => 'rejected'
                ]);
            }


            return $payment;

        }, 3);
    }
}
