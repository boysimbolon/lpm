<?php

namespace Database\Seeders;

use App\Models\Users;
use App\Models\Fakultas;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Fakultas 2
        Fakultas::create([
            'fakultas' => 'Fakultas Keguruan dan Ilmu Pendidikan',
            'prodi' => 'Pendidikan Bahasa Inggris'
        ]);

        // Fakultas 3
        Fakultas::create([
            'fakultas' => 'Fakultas Keguruan dan Ilmu Pendidikan',
            'prodi' => 'Pendidikan Matematika'
        ]);

        // Fakultas 4
        Fakultas::create([
            'fakultas' => 'Fakultas Filsafat',
            'prodi' => 'Ilmu Filsafat'
        ]);

        // Fakultas 5
        Fakultas::create([
            'fakultas' => 'Fakultas Filsafat',
            'prodi' => 'Magister Ilmu Filsafat'
        ]);

        // Fakultas 6
        Fakultas::create([
            'fakultas' => 'Fakultas Ekonomi',
            'prodi' => 'Akuntansi'
        ]);

        // Fakultas 7
        Fakultas::create([
            'fakultas' => 'Fakultas Ekonomi',
            'prodi' => 'Bisnis Digital'
        ]);

        // Fakultas 8
        Fakultas::create([
            'fakultas' => 'Fakultas Ekonomi',
            'prodi' => 'Manajemen'
        ]);

        // Fakultas 9
        Fakultas::create([
            'fakultas' => 'Fakultas Ekonomi',
            'prodi' => 'Magister Manajemen'
        ]);

        // Fakultas 10
        Fakultas::create([
            'fakultas' => 'Fakultas Matematika dan Ilmu Pengetahuan Alam',
            'prodi' => 'Biologi'
        ]);

        // Fakultas 11
        Fakultas::create([
            'fakultas' => 'Fakultas Matematika dan Ilmu Pengetahuan Alam',
            'prodi' => 'Farmasi'
        ]);

        // Fakultas 12
        Fakultas::create([
            'fakultas' => 'Fakultas Ilmu Keperawatan',
            'prodi' => 'Ilmu Keperawatan'
        ]);

        // Fakultas 13
        Fakultas::create([
            'fakultas' => 'Fakultas Ilmu Keperawatan',
            'prodi' => 'Keperawatan Umum'
        ]);

        // Fakultas 14
        Fakultas::create([
            'fakultas' => 'Fakultas Ilmu Keperawatan',
            'prodi' => 'Profesi NERS'
        ]);

        // Fakultas 15
        Fakultas::create([
            'fakultas' => 'Fakultas Ilmu Keperawatan',
            'prodi' => 'Magister Keperawatan'
        ]);

        // Fakultas 16
        Fakultas::create([
            'fakultas' => 'Fakultas Teknologi Informasi',
            'prodi' => 'Sistem Informasi'
        ]);

        // Audite
        Users::create([
            'username' => 'audite2',
            'password' => Hash::make('123'),
            'name' => 'Hendri Prastyo, S.Pd., M.PMat',
            'id_fakultas' => 2,
            'id_roles' => 2,
        ]);
    }
}
