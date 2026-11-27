<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use LogicException;

class DemoUserSeeder extends Seeder
{
    /**
     * Buat akun demo lokal tanpa mengubah akun yang sudah tersedia.
     */
    public function run(): void
    {
        if (! app()->environment('local', 'testing')) {
            throw new LogicException('DemoUserSeeder hanya untuk APP_ENV=local atau testing.');
        }

        foreach ([
            ['name' => 'Admin SiTOKO', 'email' => 'admin@sitoko.test', 'role' => 'admin'],
            ['name' => 'Pegawai SiTOKO', 'email' => 'pegawai@sitoko.test', 'role' => 'pegawai'],
            ['name' => 'Pembeli SiTOKO', 'email' => 'pembeli@sitoko.test', 'role' => 'user'],
        ] as $account) {
            User::firstOrCreate(
                ['email' => $account['email']],
                $account + ['password' => Hash::make('password123')],
            );
        }
    }
}
