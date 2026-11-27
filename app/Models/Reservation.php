<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'admin_note',
        'verified_by',
        'verified_at'
    ];

    protected function casts(): array
    {
        return [
            'reservation_date' => 'date',
            'total_amount' => 'decimal:2',
            'verified_at' => 'datetime'
        ];
    }

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
        return $this->belongsTo(User::class, 'verified_by');
    }
}