<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentService $paymentService
    ) {}

    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => 'nullable|string|max:255',
            'status' => 'nullable|in:all,pending,approved,rejected'
        ]);

        $search = trim($validated['search'] ?? '');
        $status = $validated['status'] ?? 'all';

        // Statistics
        $totalPayments = Payment::count();

        $pendingPayments = Payment::where(
            'status', 'pending'
        )->count();

        $approvedPayments = Payment::where(
            'status', 'approved'
        )->count();

        $rejectedPayments = Payment::where(
            'status', 'rejected'
        )->count();

        $totalRevenue = Payment::where(
            'status', 'approved'
        )->sum('amount');

        // Payment list
        $payments = Payment::with([
            'reservation.user',
            'reservation.schedule.doctor',
            'verifier'
        ])

        ->when($search !== '', function ($query) use ($search) {

            $query->where(function ($query) use ($search) {

                $query->whereHas(
                    'reservation',
                    function ($query) use ($search) {

                        $query->where(
                            'reservation_code',
                            'like',
                            '%' . $search . '%'
                        );
                    }
                )

                ->orWhereHas(
                    'reservation.user',
                    function ($query) use ($search) {

                        $query->where(
                            'name',
                            'like',
                            '%' . $search . '%'
                        )->orWhere(
                            'email',
                            'like',
                            '%' . $search . '%'
                        );
                    }
                )

                ->orWhereHas(
                    'reservation.schedule.doctor',
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

        ->when($status !== 'all', function ($query) use ($status) {

            $query->where('status', $status);

        })

        ->orderByRaw("
            CASE WHEN status = 'pending' THEN 0 ELSE 1 END
        ")

        ->orderByDesc('updated_at')

        ->paginate(10)
        ->withQueryString();

        return view('admin.payments.index', compact(
            'payments',
            'search',
            'status',
            'totalPayments',
            'pendingPayments',
            'approvedPayments',
            'rejectedPayments',
            'totalRevenue'
        ));
    }

    public function proof(Payment $payment)
    {
        $disk = Storage::disk('local');

        abort_unless(
            $disk->exists($payment->payment_proof),
            404,
            'Bukti pembayaran tidak ditemukan.'
        );

        return $disk->response(
            $payment->payment_proof,
            null,
            [
                'Cache-Control' => 'private, no-store',
                'X-Content-Type-Options' => 'nosniff'
            ],
            'inline'
        );
    }

    public function updateStatus(
        Request $request,
        Payment $payment
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

        $payment = $this->paymentService->verifyPayment(
            $payment,
            $request->user(),
            $validated['status'],
            $validated['admin_note'] ?? null
        );

        $message = $payment->status === 'approved'

            ? 'Pembayaran berhasil disetujui. '
                . 'Reservasi siap diverifikasi.'

            : 'Pembayaran ditolak dan reservasi dihentikan. '
                . 'Slot pemeriksaan kembali tersedia '
                . 'jika waktunya belum berlalu.';
                
        return back()->with('success', $message);
    }
}
