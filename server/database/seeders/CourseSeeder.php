<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Module;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil instructor IDs (user 2, 3, 4)
        $andi  = User::where('email', 'andi@lms.com')->first();   // Web Dev & Backend
        $siti  = User::where('email', 'siti@lms.com')->first();   // Data Science
        $fajar = User::where('email', 'fajar@lms.com')->first();  // Mobile & UI/UX

        // ═══════════════════════════════════════════════════════════════
        // COURSES DATA — 10 kursus realistis
        // ═══════════════════════════════════════════════════════════════
        $coursesData = [

            // ─── Course 1: Laravel (Andi, Web Dev, Free, Published) ───
            [
                'title'         => 'Laravel 11 Untuk Pemula',
                'description'   => 'Pelajari framework Laravel dari nol hingga mampu membuat aplikasi web fullstack. Kursus ini mencakup routing, controller, Eloquent ORM, authentication, dan deployment.',
                'instructor_id' => $andi->id,
                'category_id'   => 1, // Web Development
                'type'          => 'free',
                'price'         => 0,
                'status'        => 'published',
                'thumbnail_url' => 'https://res.cloudinary.com/demo/image/upload/v1/lms/courses/laravel-pemula.jpg',
                'modules' => [
                    [
                        'title' => 'Pengenalan Laravel',
                        'order' => 1,
                        'lessons' => [
                            ['title' => 'Apa itu Laravel?',                'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=example1', 'content' => null, 'duration' => 12, 'order' => 1],
                            ['title' => 'Instalasi & Setup Environment',   'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=example2', 'content' => null, 'duration' => 18, 'order' => 2],
                            ['title' => 'Struktur Folder Laravel',         'content_type' => 'text',  'content_url' => null, 'content' => 'Laravel menggunakan arsitektur MVC (Model-View-Controller). Folder `app/` berisi logic aplikasi, `routes/` berisi definisi URL, `resources/views/` berisi template Blade, dan `database/` berisi migration serta seeder.', 'duration' => 8, 'order' => 3],
                        ],
                    ],
                    [
                        'title' => 'Routing & Controller',
                        'order' => 2,
                        'lessons' => [
                            ['title' => 'Basic Routing di Laravel',        'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=example3', 'content' => null, 'duration' => 15, 'order' => 1],
                            ['title' => 'Membuat Controller Pertama',     'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=example4', 'content' => null, 'duration' => 20, 'order' => 2],
                            ['title' => 'Route Parameters & Middleware',   'content_type' => 'mixed', 'content_url' => 'https://www.youtube.com/watch?v=example5', 'content' => 'Middleware adalah filter yang dieksekusi sebelum request mencapai controller. Contoh: `auth`, `throttle`, `verified`.', 'duration' => 22, 'order' => 3],
                        ],
                    ],
                    [
                        'title' => 'Eloquent ORM & Database',
                        'order' => 3,
                        'lessons' => [
                            ['title' => 'Migration & Schema Builder',      'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=example6', 'content' => null, 'duration' => 16, 'order' => 1],
                            ['title' => 'Model & Eloquent CRUD',          'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=example7', 'content' => null, 'duration' => 25, 'order' => 2],
                            ['title' => 'Relationships: HasMany & BelongsTo', 'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=example8', 'content' => null, 'duration' => 30, 'order' => 3],
                            ['title' => 'Query Scopes & Pagination',      'content_type' => 'text',  'content_url' => null, 'content' => 'Query scopes memungkinkan kita mendefinisikan constraint query yang reusable. Contoh: `scopePublished`, `scopeActive`. Pagination menggunakan `->paginate(15)`.', 'duration' => 10, 'order' => 4],
                        ],
                    ],
                    [
                        'title' => 'Authentication & API',
                        'order' => 4,
                        'lessons' => [
                            ['title' => 'Laravel Sanctum Setup',           'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=example9', 'content' => null, 'duration' => 20, 'order' => 1],
                            ['title' => 'Login & Register API',           'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=example10', 'content' => null, 'duration' => 28, 'order' => 2],
                            ['title' => 'Protecting Routes dengan Middleware', 'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=example11', 'content' => null, 'duration' => 15, 'order' => 3],
                        ],
                    ],
                ],
            ],

            // ─── Course 2: Vue.js (Andi, Web Dev, Paid, Published) ───
            [
                'title'         => 'Vue.js 3 Masterclass: Composition API',
                'description'   => 'Kuasai Vue.js 3 dengan Composition API, Pinia state management, Vue Router, dan integrasi dengan REST API. Cocok untuk developer yang ingin membangun SPA modern.',
                'instructor_id' => $andi->id,
                'category_id'   => 1, // Web Development
                'type'          => 'paid',
                'price'         => 299000,
                'status'        => 'published',
                'thumbnail_url' => 'https://res.cloudinary.com/demo/image/upload/v1/lms/courses/vuejs-masterclass.jpg',
                'modules' => [
                    [
                        'title' => 'Fundamental Vue.js 3',
                        'order' => 1,
                        'lessons' => [
                            ['title' => 'Mengapa Vue.js 3?',              'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=vue1', 'content' => null, 'duration' => 10, 'order' => 1],
                            ['title' => 'Setup Project dengan Vite',      'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=vue2', 'content' => null, 'duration' => 14, 'order' => 2],
                            ['title' => 'Template Syntax & Directives',   'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=vue3', 'content' => null, 'duration' => 22, 'order' => 3],
                            ['title' => 'Reactive Data dengan ref & reactive', 'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=vue4', 'content' => null, 'duration' => 25, 'order' => 4],
                        ],
                    ],
                    [
                        'title' => 'Composition API Deep Dive',
                        'order' => 2,
                        'lessons' => [
                            ['title' => 'setup() Function & Script Setup', 'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=vue5', 'content' => null, 'duration' => 20, 'order' => 1],
                            ['title' => 'Computed & Watchers',            'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=vue6', 'content' => null, 'duration' => 18, 'order' => 2],
                            ['title' => 'Composables: Reusable Logic',    'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=vue7', 'content' => null, 'duration' => 28, 'order' => 3],
                        ],
                    ],
                    [
                        'title' => 'State Management dengan Pinia',
                        'order' => 3,
                        'lessons' => [
                            ['title' => 'Instalasi & Setup Pinia',        'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=vue8', 'content' => null, 'duration' => 12, 'order' => 1],
                            ['title' => 'Membuat Store Pertama',          'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=vue9', 'content' => null, 'duration' => 20, 'order' => 2],
                            ['title' => 'Actions, Getters & Persist',     'content_type' => 'mixed', 'content_url' => 'https://www.youtube.com/watch?v=vue10', 'content' => 'Pinia mendukung plugin pinia-plugin-persistedstate untuk menyimpan state di localStorage secara otomatis.', 'duration' => 24, 'order' => 3],
                        ],
                    ],
                ],
            ],

            // ─── Course 3: Python Data Science (Siti, Data Science, Paid, Published) ───
            [
                'title'         => 'Python untuk Data Science',
                'description'   => 'Belajar analisis data menggunakan Python, Pandas, NumPy, dan Matplotlib. Dari data cleaning hingga visualisasi profesional untuk membuat keputusan berbasis data.',
                'instructor_id' => $siti->id,
                'category_id'   => 2, // Data Science
                'type'          => 'paid',
                'price'         => 399000,
                'status'        => 'published',
                'thumbnail_url' => 'https://res.cloudinary.com/demo/image/upload/v1/lms/courses/python-datascience.jpg',
                'modules' => [
                    [
                        'title' => 'Python Fundamental untuk Data',
                        'order' => 1,
                        'lessons' => [
                            ['title' => 'Setup Python & Jupyter Notebook', 'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=py1', 'content' => null, 'duration' => 15, 'order' => 1],
                            ['title' => 'Variabel, Tipe Data & Operator', 'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=py2', 'content' => null, 'duration' => 20, 'order' => 2],
                            ['title' => 'List, Dictionary & Looping',     'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=py3', 'content' => null, 'duration' => 25, 'order' => 3],
                        ],
                    ],
                    [
                        'title' => 'Pandas & Data Manipulation',
                        'order' => 2,
                        'lessons' => [
                            ['title' => 'Membaca Data dari CSV & Excel',  'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=py4', 'content' => null, 'duration' => 18, 'order' => 1],
                            ['title' => 'DataFrame: Filtering & Sorting', 'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=py5', 'content' => null, 'duration' => 22, 'order' => 2],
                            ['title' => 'Data Cleaning & Missing Values', 'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=py6', 'content' => null, 'duration' => 28, 'order' => 3],
                            ['title' => 'GroupBy & Aggregation',          'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=py7', 'content' => null, 'duration' => 20, 'order' => 4],
                        ],
                    ],
                    [
                        'title' => 'Visualisasi Data',
                        'order' => 3,
                        'lessons' => [
                            ['title' => 'Matplotlib: Grafik Dasar',       'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=py8', 'content' => null, 'duration' => 20, 'order' => 1],
                            ['title' => 'Seaborn: Statistical Visualization', 'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=py9', 'content' => null, 'duration' => 25, 'order' => 2],
                            ['title' => 'Membuat Dashboard Sederhana',    'content_type' => 'mixed', 'content_url' => 'https://www.youtube.com/watch?v=py10', 'content' => 'Gunakan Plotly atau Streamlit untuk membuat dashboard interaktif dari data yang sudah dianalisis.', 'duration' => 35, 'order' => 3],
                        ],
                    ],
                ],
            ],

            // ─── Course 4: Machine Learning (Siti, Data Science, Paid, Published) ───
            [
                'title'         => 'Machine Learning: Dari Teori ke Praktik',
                'description'   => 'Pelajari konsep machine learning dan implementasinya dengan scikit-learn. Mulai dari supervised learning, unsupervised learning, hingga model evaluation.',
                'instructor_id' => $siti->id,
                'category_id'   => 2, // Data Science
                'type'          => 'paid',
                'price'         => 499000,
                'status'        => 'published',
                'thumbnail_url' => 'https://res.cloudinary.com/demo/image/upload/v1/lms/courses/machine-learning.jpg',
                'modules' => [
                    [
                        'title' => 'Fundamental Machine Learning',
                        'order' => 1,
                        'lessons' => [
                            ['title' => 'Apa itu Machine Learning?',      'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=ml1', 'content' => null, 'duration' => 15, 'order' => 1],
                            ['title' => 'Supervised vs Unsupervised',     'content_type' => 'text',  'content_url' => null, 'content' => 'Supervised learning menggunakan data berlabel untuk training, sedangkan unsupervised learning mencari pola tersembunyi dalam data tanpa label.', 'duration' => 10, 'order' => 2],
                            ['title' => 'Setup Environment ML',          'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=ml2', 'content' => null, 'duration' => 12, 'order' => 3],
                        ],
                    ],
                    [
                        'title' => 'Supervised Learning',
                        'order' => 2,
                        'lessons' => [
                            ['title' => 'Linear Regression',              'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=ml3', 'content' => null, 'duration' => 30, 'order' => 1],
                            ['title' => 'Logistic Regression & Classification', 'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=ml4', 'content' => null, 'duration' => 35, 'order' => 2],
                            ['title' => 'Decision Tree & Random Forest', 'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=ml5', 'content' => null, 'duration' => 40, 'order' => 3],
                        ],
                    ],
                ],
            ],

            // ─── Course 5: Flutter (Fajar, Mobile Dev, Free, Published) ───
            [
                'title'         => 'Flutter Fundamental: Cross-Platform App',
                'description'   => 'Bangun aplikasi mobile cross-platform dengan Flutter dan Dart. Pelajari widget system, state management, dan cara membangun UI yang beautiful.',
                'instructor_id' => $fajar->id,
                'category_id'   => 3, // Mobile Development
                'type'          => 'free',
                'price'         => 0,
                'status'        => 'published',
                'thumbnail_url' => 'https://res.cloudinary.com/demo/image/upload/v1/lms/courses/flutter-fundamental.jpg',
                'modules' => [
                    [
                        'title' => 'Dart Programming Language',
                        'order' => 1,
                        'lessons' => [
                            ['title' => 'Kenapa Dart & Flutter?',         'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=fl1', 'content' => null, 'duration' => 10, 'order' => 1],
                            ['title' => 'Dart Basics: Variables & Functions', 'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=fl2', 'content' => null, 'duration' => 20, 'order' => 2],
                            ['title' => 'OOP di Dart',                    'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=fl3', 'content' => null, 'duration' => 25, 'order' => 3],
                        ],
                    ],
                    [
                        'title' => 'Flutter Widget System',
                        'order' => 2,
                        'lessons' => [
                            ['title' => 'Stateless vs Stateful Widget',   'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=fl4', 'content' => null, 'duration' => 18, 'order' => 1],
                            ['title' => 'Layout: Row, Column, Stack',    'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=fl5', 'content' => null, 'duration' => 25, 'order' => 2],
                            ['title' => 'ListView & GridView',           'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=fl6', 'content' => null, 'duration' => 22, 'order' => 3],
                            ['title' => 'Navigation & Routing',          'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=fl7', 'content' => null, 'duration' => 20, 'order' => 4],
                        ],
                    ],
                    [
                        'title' => 'State Management & API',
                        'order' => 3,
                        'lessons' => [
                            ['title' => 'setState & Provider Pattern',   'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=fl8', 'content' => null, 'duration' => 28, 'order' => 1],
                            ['title' => 'HTTP Request & REST API',       'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=fl9', 'content' => null, 'duration' => 30, 'order' => 2],
                        ],
                    ],
                ],
            ],

            // ─── Course 6: UI/UX Design (Fajar, UI/UX, Paid, Published) ───
            [
                'title'         => 'UI/UX Design dengan Figma',
                'description'   => 'Pelajari prinsip desain UI/UX dan implementasinya menggunakan Figma. Dari wireframe hingga prototype interaktif siap handoff ke developer.',
                'instructor_id' => $fajar->id,
                'category_id'   => 4, // UI/UX Design
                'type'          => 'paid',
                'price'         => 249000,
                'status'        => 'published',
                'thumbnail_url' => 'https://res.cloudinary.com/demo/image/upload/v1/lms/courses/uiux-figma.jpg',
                'modules' => [
                    [
                        'title' => 'Prinsip Dasar UI/UX',
                        'order' => 1,
                        'lessons' => [
                            ['title' => 'UI vs UX: Apa Bedanya?',        'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=ux1', 'content' => null, 'duration' => 12, 'order' => 1],
                            ['title' => 'User Research & Persona',       'content_type' => 'text',  'content_url' => null, 'content' => 'User persona adalah representasi fiktif dari target user. Buat berdasarkan data riset: demografi, goals, pain points, dan behavior patterns.', 'duration' => 15, 'order' => 2],
                            ['title' => 'Design Thinking Process',       'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=ux2', 'content' => null, 'duration' => 20, 'order' => 3],
                        ],
                    ],
                    [
                        'title' => 'Figma Mastery',
                        'order' => 2,
                        'lessons' => [
                            ['title' => 'Figma Interface & Tools',       'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=ux3', 'content' => null, 'duration' => 18, 'order' => 1],
                            ['title' => 'Auto Layout & Components',      'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=ux4', 'content' => null, 'duration' => 28, 'order' => 2],
                            ['title' => 'Design System & Variables',     'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=ux5', 'content' => null, 'duration' => 35, 'order' => 3],
                            ['title' => 'Prototyping & Interaksi',       'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=ux6', 'content' => null, 'duration' => 30, 'order' => 4],
                        ],
                    ],
                ],
            ],

            // ─── Course 7: REST API (Andi, Web Dev, Paid, Published) ───
            [
                'title'         => 'RESTful API Design & Best Practices',
                'description'   => 'Desain API yang scalable dan maintainable. Pelajari REST conventions, versioning, pagination, error handling, dan dokumentasi dengan OpenAPI/Swagger.',
                'instructor_id' => $andi->id,
                'category_id'   => 1, // Web Development
                'type'          => 'paid',
                'price'         => 199000,
                'status'        => 'published',
                'thumbnail_url' => 'https://res.cloudinary.com/demo/image/upload/v1/lms/courses/restful-api.jpg',
                'modules' => [
                    [
                        'title' => 'REST API Fundamentals',
                        'order' => 1,
                        'lessons' => [
                            ['title' => 'HTTP Methods & Status Codes',    'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=api1', 'content' => null, 'duration' => 15, 'order' => 1],
                            ['title' => 'Resource Naming Conventions',    'content_type' => 'text',  'content_url' => null, 'content' => 'Gunakan kata benda plural untuk resource: `/users`, `/courses`, `/enrollments`. Hindari verb: `/getUsers` ❌. Gunakan nesting untuk relasi: `/courses/{id}/modules`.', 'duration' => 10, 'order' => 2],
                            ['title' => 'Request & Response Format',     'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=api2', 'content' => null, 'duration' => 20, 'order' => 3],
                        ],
                    ],
                    [
                        'title' => 'Advanced API Patterns',
                        'order' => 2,
                        'lessons' => [
                            ['title' => 'Pagination & Filtering',        'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=api3', 'content' => null, 'duration' => 22, 'order' => 1],
                            ['title' => 'Authentication: JWT vs Sanctum', 'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=api4', 'content' => null, 'duration' => 28, 'order' => 2],
                            ['title' => 'Error Handling & Validation',   'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=api5', 'content' => null, 'duration' => 18, 'order' => 3],
                        ],
                    ],
                ],
            ],

            // ─── Course 8: React Native (Fajar, Mobile Dev, Paid, Published) ───
            [
                'title'         => 'React Native: Build Mobile Apps with JavaScript',
                'description'   => 'Bangun aplikasi mobile native menggunakan React Native dan Expo. Pelajari component-based architecture, React Navigation, dan integrasi dengan backend API.',
                'instructor_id' => $fajar->id,
                'category_id'   => 3, // Mobile Development
                'type'          => 'paid',
                'price'         => 349000,
                'status'        => 'published',
                'thumbnail_url' => 'https://res.cloudinary.com/demo/image/upload/v1/lms/courses/react-native.jpg',
                'modules' => [
                    [
                        'title' => 'React Native Basics',
                        'order' => 1,
                        'lessons' => [
                            ['title' => 'Setup Expo & Project Structure', 'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=rn1', 'content' => null, 'duration' => 15, 'order' => 1],
                            ['title' => 'Core Components: View, Text, Image', 'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=rn2', 'content' => null, 'duration' => 20, 'order' => 2],
                            ['title' => 'Styling dengan StyleSheet',     'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=rn3', 'content' => null, 'duration' => 18, 'order' => 3],
                        ],
                    ],
                    [
                        'title' => 'Navigation & State',
                        'order' => 2,
                        'lessons' => [
                            ['title' => 'React Navigation Setup',        'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=rn4', 'content' => null, 'duration' => 22, 'order' => 1],
                            ['title' => 'Stack, Tab, & Drawer Navigator', 'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=rn5', 'content' => null, 'duration' => 30, 'order' => 2],
                            ['title' => 'State Management dengan Context', 'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=rn6', 'content' => null, 'duration' => 25, 'order' => 3],
                        ],
                    ],
                ],
            ],

            // ─── Course 9: Digital Marketing (Siti, Business, Free, Published) ───
            [
                'title'         => 'Digital Marketing untuk UMKM',
                'description'   => 'Pelajari strategi digital marketing yang efektif untuk usaha kecil menengah. Dari social media marketing, SEO dasar, hingga Google Ads.',
                'instructor_id' => $siti->id,
                'category_id'   => 5, // Business
                'type'          => 'free',
                'price'         => 0,
                'status'        => 'published',
                'thumbnail_url' => 'https://res.cloudinary.com/demo/image/upload/v1/lms/courses/digital-marketing.jpg',
                'modules' => [
                    [
                        'title' => 'Pengenalan Digital Marketing',
                        'order' => 1,
                        'lessons' => [
                            ['title' => 'Landscape Digital Marketing 2026', 'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=dm1', 'content' => null, 'duration' => 12, 'order' => 1],
                            ['title' => 'Menentukan Target Audience',    'content_type' => 'text',  'content_url' => null, 'content' => 'Gunakan framework STP (Segmenting, Targeting, Positioning) untuk menentukan target audience yang tepat. Buat buyer persona berdasarkan demografi, psikografi, dan perilaku online.', 'duration' => 10, 'order' => 2],
                            ['title' => 'Content Marketing Strategy',    'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=dm2', 'content' => null, 'duration' => 18, 'order' => 3],
                        ],
                    ],
                    [
                        'title' => 'Social Media & SEO',
                        'order' => 2,
                        'lessons' => [
                            ['title' => 'Instagram & TikTok Marketing',  'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=dm3', 'content' => null, 'duration' => 22, 'order' => 1],
                            ['title' => 'SEO Dasar: On-Page & Off-Page', 'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=dm4', 'content' => null, 'duration' => 28, 'order' => 2],
                        ],
                    ],
                ],
            ],

            // ─── Course 10: Kubernetes (Andi, Web Dev, Paid, DRAFT) ───
            [
                'title'         => 'Kubernetes & Docker untuk Production',
                'description'   => 'Deploy aplikasi ke production dengan Docker container dan Kubernetes orchestration. Pelajari CI/CD, monitoring, dan scaling strategies.',
                'instructor_id' => $andi->id,
                'category_id'   => 1, // Web Development
                'type'          => 'paid',
                'price'         => 449000,
                'status'        => 'draft',  // Belum dipublish
                'thumbnail_url' => 'https://res.cloudinary.com/demo/image/upload/v1/lms/courses/kubernetes-docker.jpg',
                'modules' => [
                    [
                        'title' => 'Docker Fundamentals',
                        'order' => 1,
                        'lessons' => [
                            ['title' => 'Apa itu Container?',            'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=k8s1', 'content' => null, 'duration' => 14, 'order' => 1],
                            ['title' => 'Dockerfile & Docker Compose',  'content_type' => 'video', 'content_url' => 'https://www.youtube.com/watch?v=k8s2', 'content' => null, 'duration' => 25, 'order' => 2],
                        ],
                    ],
                ],
            ],
        ];

        // ═══════════════════════════════════════════════════════════════
        // INSERT DATA
        // ═══════════════════════════════════════════════════════════════
        foreach ($coursesData as $courseData) {
            $modules = $courseData['modules'];
            unset($courseData['modules']);

            $course = Course::create($courseData);

            foreach ($modules as $moduleData) {
                $lessons = $moduleData['lessons'];
                unset($moduleData['lessons']);

                $module = $course->modules()->create($moduleData);

                foreach ($lessons as $lessonData) {
                    $module->lessons()->create($lessonData);
                }
            }
        }
    }
}
