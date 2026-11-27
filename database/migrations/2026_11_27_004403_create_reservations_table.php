<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();

            $table->string('reservation_code', 30)->unique();

            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('schedule_id')
                ->constrained('schedules')
                ->restrictOnDelete();

            $table->date('reservation_date');

            $table->time('start_time');
            $table->time('end_time');

            $table->text('complaint');

            $table->decimal('total_amount', 12, 2);

            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'confirmed',
                'completed',
                'cancelled'
            ])->default('pending');

            $table->text('admin_note')->nullable();

            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('verified_at')->nullable();

            $table->timestamps();

            $table->index([
                'schedule_id',
                'reservation_date',
                'start_time',
                'status'
            ], 'reservations_slot_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};