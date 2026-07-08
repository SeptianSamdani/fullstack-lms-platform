<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua kursus published
        $laravelCourse   = Course::where('title', 'like', 'Laravel%')->first();           // id 1 - free
        $vueCourse       = Course::where('title', 'like', 'Vue.js%')->first();             // id 2 - paid
        $pythonCourse    = Course::where('title', 'like', 'Python%')->first();             // id 3 - paid
        $mlCourse        = Course::where('title', 'like', 'Machine Learning%')->first();   // id 4 - paid
        $flutterCourse   = Course::where('title', 'like', 'Flutter%')->first();            // id 5 - free
        $uiuxCourse      = Course::where('title', 'like', 'UI/UX%')->first();              // id 6 - paid
        $apiCourse       = Course::where('title', 'like', 'RESTful%')->first();            // id 7 - paid
        $rnCourse        = Course::where('title', 'like', 'React Native%')->first();       // id 8 - paid
        $marketingCourse = Course::where('title', 'like', 'Digital Marketing%')->first();  // id 9 - free

        // Ambil students
        $budi   = User::where('email', 'budi@lms.com')->first();
        $citra  = User::where('email', 'citra@lms.com')->first();
        $dimas  = User::where('email', 'dimas@lms.com')->first();
        $gilang = User::where('email', 'gilang@lms.com')->first();
        $hana   = User::where('email', 'hana@lms.com')->first();
        $irfan  = User::where('email', 'irfan@lms.com')->first();

        $enrollments = [
            // ─── Budi (subscription aktif): 3 kursus (2 paid + 1 free) ───
            ['user_id' => $budi->id, 'course_id' => $laravelCourse->id,  'status' => 'active',    'enrolled_at' => Carbon::now()->subDays(25)],
            ['user_id' => $budi->id, 'course_id' => $vueCourse->id,      'status' => 'active',    'enrolled_at' => Carbon::now()->subDays(18)],
            ['user_id' => $budi->id, 'course_id' => $apiCourse->id,      'status' => 'active',    'enrolled_at' => Carbon::now()->subDays(10)],

            // ─── Citra (subscription aktif): 3 kursus — 1 sudah completed ───
            ['user_id' => $citra->id, 'course_id' => $flutterCourse->id,  'status' => 'completed', 'enrolled_at' => Carbon::now()->subDays(50)],
            ['user_id' => $citra->id, 'course_id' => $pythonCourse->id,   'status' => 'active',    'enrolled_at' => Carbon::now()->subDays(30)],
            ['user_id' => $citra->id, 'course_id' => $uiuxCourse->id,     'status' => 'active',    'enrolled_at' => Carbon::now()->subDays(15)],

            // ─── Dimas (tanpa subscription): hanya kursus gratis ───
            ['user_id' => $dimas->id, 'course_id' => $laravelCourse->id,    'status' => 'active', 'enrolled_at' => Carbon::now()->subDays(12)],
            ['user_id' => $dimas->id, 'course_id' => $flutterCourse->id,    'status' => 'active', 'enrolled_at' => Carbon::now()->subDays(8)],
            ['user_id' => $dimas->id, 'course_id' => $marketingCourse->id,  'status' => 'active', 'enrolled_at' => Carbon::now()->subDays(5)],

            // ─── Gilang (subscription expired): enrolled saat masih aktif ───
            ['user_id' => $gilang->id, 'course_id' => $laravelCourse->id,  'status' => 'active',    'enrolled_at' => Carbon::now()->subDays(40)],
            ['user_id' => $gilang->id, 'course_id' => $mlCourse->id,       'status' => 'active',    'enrolled_at' => Carbon::now()->subDays(35)],

            // ─── Hana (subscription baru): baru enroll 1 kursus ───
            ['user_id' => $hana->id, 'course_id' => $pythonCourse->id,    'status' => 'active', 'enrolled_at' => Carbon::now()->subDays(2)],
            ['user_id' => $hana->id, 'course_id' => $marketingCourse->id, 'status' => 'active', 'enrolled_at' => Carbon::now()->subDays(1)],

            // ─── Irfan (subscription aktif): paling aktif, 5 kursus ───
            ['user_id' => $irfan->id, 'course_id' => $laravelCourse->id,    'status' => 'completed', 'enrolled_at' => Carbon::now()->subDays(60)],
            ['user_id' => $irfan->id, 'course_id' => $vueCourse->id,        'status' => 'active',    'enrolled_at' => Carbon::now()->subDays(45)],
            ['user_id' => $irfan->id, 'course_id' => $pythonCourse->id,     'status' => 'active',    'enrolled_at' => Carbon::now()->subDays(30)],
            ['user_id' => $irfan->id, 'course_id' => $flutterCourse->id,    'status' => 'active',    'enrolled_at' => Carbon::now()->subDays(20)],
            ['user_id' => $irfan->id, 'course_id' => $marketingCourse->id,  'status' => 'active',    'enrolled_at' => Carbon::now()->subDays(7)],
        ];

        foreach ($enrollments as $enrollment) {
            Enrollment::create($enrollment);
        }
    }
}
