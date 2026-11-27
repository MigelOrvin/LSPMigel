<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {

            $table->timestamp('expires_at')
                ->nullable()
                ->after('status');

            $table->index(
                ['status', 'expires_at'],
                'reservations_expiration_index'
            );
        });

        // Tambahkan status expired
        DB::statement("
            ALTER TABLE reservations
            MODIFY COLUMN status ENUM(
                'pending',
                'approved',
                'rejected',
                'confirmed',
                'completed',
                'cancelled',
                'expired'
            ) NOT NULL DEFAULT 'pending'
        ");

        // Sesuaikan reservasi lama yang pembayarannya ditolak
        DB::statement("
            UPDATE reservations
            INNER JOIN payments
                ON payments.reservation_id = reservations.id
            SET reservations.status = 'rejected'
            WHERE reservations.status = 'pending'
                AND payments.status = 'rejected'
        ");

        // Berikan deadline pada reservasi lama yang belum membayar
        DB::statement("
            UPDATE reservations
            SET expires_at = DATE_ADD(
                created_at,
                INTERVAL 5 MINUTE
            )
            WHERE status = 'pending'
                AND NOT EXISTS (
                    SELECT 1
                    FROM payments
                    WHERE payments.reservation_id = reservations.id
                )
        ");

        // Kedaluwarsakan reservasi lama yang melewati deadline
        DB::table('reservations')
            ->where('status', 'pending')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now())
            ->whereNotExists(function ($query) {
                $query->selectRaw('1')
                    ->from('payments')
                    ->whereColumn(
                        'payments.reservation_id',
                        'reservations.id'
                    );
            })
            ->update([
                'status' => 'expired',
                'updated_at' => now()
            ]);
    }

    public function down(): void
    {
        // Status expired harus dikonversi sebelum enum dihapus
        DB::table('reservations')
            ->where('status', 'expired')
            ->update([
                'status' => 'cancelled'
            ]);

        DB::statement("
            ALTER TABLE reservations
            MODIFY COLUMN status ENUM(
                'pending',
                'approved',
                'rejected',
                'confirmed',
                'completed',
                'cancelled'
            ) NOT NULL DEFAULT 'pending'
        ");

        Schema::table('reservations', function (Blueprint $table) {

            $table->dropIndex('reservations_expiration_index');

            $table->dropColumn('expires_at');
        });
    }
};