<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Progress;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ProgressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ═══════════════════════════════════════════════════════════════
        // Helper: ambil semua lesson IDs untuk suatu course
        // ═══════════════════════════════════════════════════════════════
        $getLessonIds = function (string $courseTitleLike): array {
            $course = Course::where('title', 'like', $courseTitleLike)->first();
            return Lesson::whereHas('module', fn($q) => $q->where('course_id', $course->id))
                ->orderBy('id')
                ->pluck('id')
                ->toArray();
        };

        $laravelLessons   = $getLessonIds('Laravel%');        // 13 lessons
        $vueLessons       = $getLessonIds('Vue.js%');          // 10 lessons
        $pythonLessons    = $getLessonIds('Python%');           // 10 lessons
        $flutterLessons   = $getLessonIds('Flutter%');          // 9 lessons
        $apiLessons       = $getLessonIds('RESTful%');          // 6 lessons
        $marketingLessons = $getLessonIds('Digital Marketing%');// 5 lessons
        $mlLessons        = $getLessonIds('Machine Learning%');// 6 lessons
        $uiuxLessons      = $getLessonIds('UI/UX%');           // 7 lessons

        // Ambil students
        $budi   = User::where('email', 'budi@lms.com')->first();
        $citra  = User::where('email', 'citra@lms.com')->first();
        $dimas  = User::where('email', 'dimas@lms.com')->first();
        $gilang = User::where('email', 'gilang@lms.com')->first();
        $hana   = User::where('email', 'hana@lms.com')->first();
        $irfan  = User::where('email', 'irfan@lms.com')->first();

        $progressData = [];

        // ═══════════════════════════════════════════════════════════════
        // Budi — Laravel: 8/13 selesai, Vue.js: 4/10, API: 2/6
        // ═══════════════════════════════════════════════════════════════
        foreach (array_slice($laravelLessons, 0, 8) as $i => $lessonId) {
            $progressData[] = [
                'user_id'      => $budi->id,
                'lesson_id'    => $lessonId,
                'completed_at' => Carbon::now()->subDays(25 - $i),
            ];
        }
        
        foreach (array_slice($vueLessons, 0, 4) as $i => $lessonId) {
            $progressData[] = [
                'user_id'      => $budi->id,
                'lesson_id'    => $lessonId,
                'completed_at' => Carbon::now()->subDays(15 - $i),
            ];
        }
        foreach (array_slice($apiLessons, 0, 2) as $i => $lessonId) {
            $progressData[] = [
                'user_id'      => $budi->id,
                'lesson_id'    => $lessonId,
                'completed_at' => Carbon::now()->subDays(8 - $i),
            ];
        }

        // ═══════════════════════════════════════════════════════════════
        // Citra — Flutter: 9/9 COMPLETED, Python: 6/10, UI/UX: 2/7
        // ═══════════════════════════════════════════════════════════════
        foreach ($flutterLessons as $i => $lessonId) {
            $progressData[] = [
                'user_id'      => $citra->id,
                'lesson_id'    => $lessonId,
                'completed_at' => Carbon::now()->subDays(50 - $i),
            ];
        }
        foreach (array_slice($pythonLessons, 0, 6) as $i => $lessonId) {
            $progressData[] = [
                'user_id'      => $citra->id,
                'lesson_id'    => $lessonId,
                'completed_at' => Carbon::now()->subDays(28 - $i),
            ];
        }
        foreach (array_slice($uiuxLessons, 0, 2) as $i => $lessonId) {
            $progressData[] = [
                'user_id'      => $citra->id,
                'lesson_id'    => $lessonId,
                'completed_at' => Carbon::now()->subDays(13 - $i),
            ];
        }

        // ═══════════════════════════════════════════════════════════════
        // Dimas — Laravel: 3/13, Flutter: 2/9, Marketing: 1/5
        // ═══════════════════════════════════════════════════════════════
        foreach (array_slice($laravelLessons, 0, 3) as $i => $lessonId) {
            $progressData[] = [
                'user_id'      => $dimas->id,
                'lesson_id'    => $lessonId,
                'completed_at' => Carbon::now()->subDays(10 - $i),
            ];
        }
        foreach (array_slice($flutterLessons, 0, 2) as $i => $lessonId) {
            $progressData[] = [
                'user_id'      => $dimas->id,
                'lesson_id'    => $lessonId,
                'completed_at' => Carbon::now()->subDays(6 - $i),
            ];
        }
        $progressData[] = [
            'user_id'      => $dimas->id,
            'lesson_id'    => $marketingLessons[0],
            'completed_at' => Carbon::now()->subDays(3),
        ];

        // ═══════════════════════════════════════════════════════════════
        // Gilang — Laravel: 5/13, ML: 3/6 (terhenti karena expired)
        // ═══════════════════════════════════════════════════════════════
        foreach (array_slice($laravelLessons, 0, 5) as $i => $lessonId) {
            $progressData[] = [
                'user_id'      => $gilang->id,
                'lesson_id'    => $lessonId,
                'completed_at' => Carbon::now()->subDays(38 - $i),
            ];
        }
        foreach (array_slice($mlLessons, 0, 3) as $i => $lessonId) {
            $progressData[] = [
                'user_id'      => $gilang->id,
                'lesson_id'    => $lessonId,
                'completed_at' => Carbon::now()->subDays(32 - $i),
            ];
        }

        // ═══════════════════════════════════════════════════════════════
        // Hana — Python: 1/10, Marketing: 0/5 (baru mulai)
        // ═══════════════════════════════════════════════════════════════
        $progressData[] = [
            'user_id'      => $hana->id,
            'lesson_id'    => $pythonLessons[0],
            'completed_at' => Carbon::now()->subDay(),
        ];

        // ═══════════════════════════════════════════════════════════════
        // Irfan (power user) — Laravel: 13/13 COMPLETED, Vue: 7/10,
        //                       Python: 4/10, Flutter: 5/9, Marketing: 3/5
        // ═══════════════════════════════════════════════════════════════
        foreach ($laravelLessons as $i => $lessonId) {
            $progressData[] = [
                'user_id'      => $irfan->id,
                'lesson_id'    => $lessonId,
                'completed_at' => Carbon::now()->subDays(55 - $i),
            ];
        }
        foreach (array_slice($vueLessons, 0, 7) as $i => $lessonId) {
            $progressData[] = [
                'user_id'      => $irfan->id,
                'lesson_id'    => $lessonId,
                'completed_at' => Carbon::now()->subDays(40 - $i),
            ];
        }
        foreach (array_slice($pythonLessons, 0, 4) as $i => $lessonId) {
            $progressData[] = [
                'user_id'      => $irfan->id,
                'lesson_id'    => $lessonId,
                'completed_at' => Carbon::now()->subDays(25 - $i),
            ];
        }
        foreach (array_slice($flutterLessons, 0, 5) as $i => $lessonId) {
            $progressData[] = [
                'user_id'      => $irfan->id,
                'lesson_id'    => $lessonId,
                'completed_at' => Carbon::now()->subDays(18 - $i),
            ];
        }
        foreach (array_slice($marketingLessons, 0, 3) as $i => $lessonId) {
            $progressData[] = [
                'user_id'      => $irfan->id,
                'lesson_id'    => $lessonId,
                'completed_at' => Carbon::now()->subDays(5 - $i),
            ];
        }

        // ═══════════════════════════════════════════════════════════════
        // INSERT ALL PROGRESS
        // ═══════════════════════════════════════════════════════════════
        foreach ($progressData as $progress) {
            Progress::create($progress);
        }
    }
}
