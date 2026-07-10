<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * Urutan penting karena ada dependency antar tabel:
     * Roles/Permissions → Users → Categories → Courses (+ Modules + Lessons)
     * → SubscriptionPlans → Subscriptions (+ Payments) → Enrollments → Progress
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,   // 1. Roles & permissions
            UserSeeder::class,             // 2. Admin, instructors, students
            CategorySeeder::class,         // 3. Kategori kursus
            CourseSeeder::class,           // 4. Kursus + modules + lessons
            SubscriptionPlanSeeder::class, // 5. Paket subscription
            SubscriptionSeeder::class,     // 6. Subscription + payments
            EnrollmentSeeder::class,       // 7. Enrollment students ke courses
            ProgressSeeder::class,         // 8. Progress belajar
            ReviewSeeder::class,           // 9. Review & rating course
        ]);
    }
}