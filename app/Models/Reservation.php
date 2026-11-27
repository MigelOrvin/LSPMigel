<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Reservation extends Model
{
    protected $fillable = [
        'reservation_code',
        'user_id',
        'schedule_id',
        'reservation_date',
        'start_time',
        'end_time',
        'complaint',
        'total_amount',
        'status',
        'expires_at',
        'admin_note',
        'verified_by',
        'verified_at'
    ];

    protected function casts(): array
    {
        return [
            'reservation_date' => 'date',
            'total_amount' => 'decimal:2',
            'expires_at' => 'datetime',
            'verified_at' => 'datetime'
        ];
    }

    /* ========================================
       RELATIONSHIPS
    ======================================== */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'verified_by'
        );
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    /* ========================================
       PAYMENT DEADLINE
    ======================================== */

    public function hasPaymentExpired(): bool
    {
        return $this->status === 'pending'
            && !$this->payment
            && $this->expires_at !== null
            && $this->expires_at->lte(now());
    }

    public function getRemainingPaymentSecondsAttribute(): int
    {
        if (
            $this->status !== 'pending' ||
            $this->payment ||
            !$this->expires_at
        ) {
            return 0;
        }

        return max(
            0,
            $this->expires_at->getTimestamp()
                - now()->getTimestamp()
        );
    }

    public function getCanPayAttribute(): bool
    {
        $appointmentTime = CarbonImmutable::parse(
            $this->reservation_date->format('Y-m-d')
                . ' '
                . $this->start_time,
            'Asia/Jakarta'
        );

        return $this->status === 'pending'
            && !$this->payment
            && $this->expires_at !== null
            && $this->expires_at->gt(now())
            && $appointmentTime->gt(
                CarbonImmutable::now('Asia/Jakarta')
            );
    }

    /* ========================================
       DISPLAY STATUS
    ======================================== */

    public function getDisplayStatusAttribute(): string
    {
        // Pembayaran ditolak memiliki status khusus
        if (
            $this->payment?->status === 'rejected' &&
            in_array(
                $this->status,
                ['pending', 'rejected'],
                true
            )
        ) {
            return 'payment_rejected';
        }

        // Deadline pembayaran telah habis
        if (
            $this->status === 'expired' ||
            $this->hasPaymentExpired()
        ) {
            return 'expired';
        }

        // Status akhir reservasi
        if ($this->status !== 'pending') {
            return $this->status;
        }

        // Status berdasarkan tahapan pembayaran
        return match ($this->payment?->status) {
            null => 'awaiting_payment',
            'pending' => 'payment_pending',
            'approved' => 'reservation_pending',
            default => 'pending'
        };
    }

    /* ========================================
       QUERY SCOPES
    ======================================== */

    public function scopeExpiredUnpaid(
        Builder $query
    ): Builder {

        return $query
            ->where('status', 'pending')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now())
            ->whereDoesntHave('payment');
    }

    public function scopeOccupiesSlot(
        Builder $query
    ): Builder {

        return $query->where(function ($query) {

            // Reservasi yang sudah disetujui
            $query->whereIn('status', [
                'approved',
                'confirmed',
                'completed'
            ])

            ->orWhere(function ($query) {

                $query->where('status', 'pending')

                    ->where(function ($query) {

                        // Bukti pembayaran sudah dikirim
                        $query->whereHas(
                            'payment',
                            function ($query) {

                                $query->whereIn('status', [
                                    'pending',
                                    'approved'
                                ]);
                            }
                        )

                        // Belum membayar, tetapi deadline masih aktif
                        ->orWhere(function ($query) {

                            $query
                                ->whereDoesntHave('payment')
                                ->where(
                                    'expires_at',
                                    '>',
                                    now()
                                );
                        });
                    });
            });
        });
    }
    public function scopeActive(Builder $query): Builder
{
    $today = now('Asia/Jakarta')->toDateString();

    return $query
        ->where('reservation_date', '>=', $today)
        ->where(function ($query) {

            $query->whereIn('status', [
                'approved',
                'confirmed'
            ])

            ->orWhere(function ($query) {

                $query->where('status', 'pending')
                    ->occupiesSlot();

            });
        });
}

    public function scopeHistory(Builder $query): Builder
    {
        return $query->whereNot(function ($query) {

            $query->active();

        });
    }
}
