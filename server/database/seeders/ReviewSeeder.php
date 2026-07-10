<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Review hanya dibuat untuk enrollment yang sudah completed
     * atau yang progress-nya cukup jauh (realistis, bukan baru daftar).
     */
    public function run(): void
    {
        $laravelCourse = Course::where('title', 'like', 'Laravel%')->first();
        $vueCourse     = Course::where('title', 'like', 'Vue.js%')->first();
        $pythonCourse  = Course::where('title', 'like', 'Python%')->first();
        $flutterCourse = Course::where('title', 'like', 'Flutter%')->first();
        $apiCourse     = Course::where('title', 'like', 'RESTful%')->first();

        $budi   = User::where('email', 'budi@lms.com')->first();
        $citra  = User::where('email', 'citra@lms.com')->first();
        $gilang = User::where('email', 'gilang@lms.com')->first();
        $irfan  = User::where('email', 'irfan@lms.com')->first();

        $reviews = [
            // Irfan — completed Laravel, power user, review positif detail
            [
                'user_id'   => $irfan->id,
                'course_id' => $laravelCourse->id,
                'rating'    => 5,
                'comment'   => 'Materinya lengkap dan mudah dipahami, contoh kasusnya relevan dengan kebutuhan kerja sehari-hari.',
            ],
            // Irfan juga aktif di Vue, sudah 7/10 lesson
            [
                'user_id'   => $irfan->id,
                'course_id' => $vueCourse->id,
                'rating'    => 4,
                'comment'   => 'Bagus untuk pemula, tapi beberapa bagian reactivity agak terburu-buru dijelaskan.',
            ],

            // Citra — completed Flutter
            [
                'user_id'   => $citra->id,
                'course_id' => $flutterCourse->id,
                'rating'    => 5,
                'comment'   => 'Instruktur menjelaskan step by step, cocok buat yang baru pindah dari native development.',
            ],
            // Citra progress lumayan di Python
            [
                'user_id'   => $citra->id,
                'course_id' => $pythonCourse->id,
                'rating'    => 4,
                'comment'   => 'Latihan-latihannya membantu, meski butuh waktu lebih lama dari perkiraan.',
            ],

            // Budi — progress lumayan di Laravel & API
            [
                'user_id'   => $budi->id,
                'course_id' => $laravelCourse->id,
                'rating'    => 4,
                'comment'   => 'Cukup jelas, tapi butuh update ke Laravel versi terbaru untuk beberapa contoh kode.',
            ],
            [
                'user_id'   => $budi->id,
                'course_id' => $apiCourse->id,
                'rating'    => 5,
                'comment'   => null,
            ],

            // Gilang — sempat belajar Laravel walau subscription sudah expired
            [
                'user_id'   => $gilang->id,
                'course_id' => $laravelCourse->id,
                'rating'    => 3,
                'comment'   => 'Kontennya oke, sayang saya belum sempat menyelesaikan sebelum subscription habis.',
            ],
        ];

        foreach ($reviews as $review) {
            Review::create($review);
        }
    }
}