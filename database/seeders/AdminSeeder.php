<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrNew([
            'email' => 'admin@dental.com',
        ]);

        if (!$admin->exists) {
            $admin->name = 'Admin';
            $admin->password = 'Admin123!';
            $admin->role = 'admin';
            $admin->status = 'approved';
            $admin->save();
        }
    }
}
