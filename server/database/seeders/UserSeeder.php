<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ═══════════════════════════════════════════════════════════════
        // Admin
        // ═══════════════════════════════════════════════════════════════
        $admin = User::create([
            'name'  => 'Rizky Admin',
            'email' => 'admin@lms.com',
            'password' => 'password',
        ]);
        $admin->assignRole('admin');

        // ═══════════════════════════════════════════════════════════════
        // Instructors (3 orang, berbeda spesialisasi)
        // ═══════════════════════════════════════════════════════════════
        $instructors = [
            ['name' => 'Andi Pratama',    'email' => 'andi@lms.com'],    // Web Dev & Backend
            ['name' => 'Siti Nurhaliza',  'email' => 'siti@lms.com'],    // Data Science & AI
            ['name' => 'Fajar Nugroho',   'email' => 'fajar@lms.com'],   // Mobile & UI/UX
        ];

        foreach ($instructors as $data) {
            $user = User::create([...$data, 'password' => 'password']);
            $user->assignRole('instructor');
        }

        // ═══════════════════════════════════════════════════════════════
        // Students (8 orang dengan berbagai kondisi)
        // ═══════════════════════════════════════════════════════════════
        $students = [
            ['name' => 'Budi Santoso',     'email' => 'budi@lms.com'],      // Punya subscription, aktif belajar
            ['name' => 'Citra Dewi',       'email' => 'citra@lms.com'],     // Punya subscription, sudah selesai 1 kursus
            ['name' => 'Dimas Prasetyo',   'email' => 'dimas@lms.com'],     // Tanpa subscription, hanya kursus gratis
            ['name' => 'Eka Putri',        'email' => 'eka@lms.com'],       // Baru daftar, belum enroll
            ['name' => 'Gilang Ramadhan',  'email' => 'gilang@lms.com'],    // Subscription expired
            ['name' => 'Hana Safitri',     'email' => 'hana@lms.com'],      // Punya subscription, baru mulai
            ['name' => 'Irfan Maulana',    'email' => 'irfan@lms.com'],     // Aktif belajar banyak kursus
            ['name' => 'Jasmine Putri',    'email' => 'jasmine@lms.com'],   // Baru daftar, belum enroll
        ];

        foreach ($students as $data) {
            $user = User::create([...$data, 'password' => 'password']);
            $user->assignRole('student');
        }
    }
}