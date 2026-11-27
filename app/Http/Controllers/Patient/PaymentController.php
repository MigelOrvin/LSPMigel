<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentService $paymentService
    ) {}

    public function create(
        Request $request,
        Reservation $reservation
    ) {

        $reservation->load([
            'schedule.doctor',
            'payment'
        ]);

        try {

            $this->paymentService->ensurePayable(
                $reservation,
                $request->user()
            );

        } catch (ValidationException $exception) {

            return redirect()
                ->route('patient.reservations.index')
                ->withErrors($exception->errors());
        }

        return view(
            'patient.payments.create',
            compact('reservation')
        );
    }

    public function store(
        Request $request,
        Reservation $reservation
    ) {

        abort_unless(
            $reservation->user_id === $request->user()->id,
            403
        );

        $validated = $request->validate([
            'payment_proof' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048'
            ]
        ], [
            'payment_proof.required' =>
                'Bukti pembayaran wajib diunggah.',

            'payment_proof.image' =>
                'Bukti pembayaran harus berupa gambar.',

            'payment_proof.mimes' =>
                'Format gambar harus JPG, PNG, atau WEBP.',

            'payment_proof.max' =>
                'Ukuran gambar maksimal 2 MB.'
        ]);

        $this->paymentService->submitPayment(
            $request->user(),
            $reservation,
            $validated['payment_proof']
        );

        return redirect()
            ->route('patient.reservations.index')
            ->with(
                'success',
                'Bukti pembayaran berhasil dikirim. '
                . 'Silakan tunggu verifikasi admin.'
            );
    }
}
