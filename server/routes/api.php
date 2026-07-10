<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\EnrollmentController;
use App\Http\Controllers\Api\LessonController;
use App\Http\Controllers\Api\ModuleController;
use App\Http\Controllers\Api\ProgressController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:auth');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:auth');
Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
Route::apiResource('courses', CourseController::class)->only(['index', 'show']);
Route::get('/courses/{course}/modules', [ModuleController::class, 'index']);
Route::get('/modules/{module}/lessons', [LessonController::class, 'index']);
Route::get('/subscription-plans', [SubscriptionController::class, 'plans']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // ─── Profile ───────────────────────────────────────────────────────────
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar']);
    Route::put('/profile/password', [ProfileController::class, 'updatePassword']);

    // ─── Dashboard (role-based) ───────────────────────────────────────────────
    Route::get('/dashboard/admin',      [DashboardController::class, 'admin'])->middleware('role:admin');
    Route::get('/dashboard/instructor', [DashboardController::class, 'instructor'])->middleware('role:instructor');
    Route::get('/dashboard/student',    [DashboardController::class, 'student'])->middleware('role:student');

    // ─── Admin: User & Role Management ───────────────────────────────────────
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/users',                   [AdminController::class, 'users']);
        Route::post('/admin/users/{user}/assign-role', [AdminController::class, 'assignRole']);
        Route::get('/admin/roles',                   [AdminController::class, 'roles']);

        // Subscription Plan CRUD (admin only)
        Route::post('/subscription-plans',                    [SubscriptionController::class, 'storePlan']);
        Route::put('/subscription-plans/{plan}',              [SubscriptionController::class, 'updatePlan']);
        Route::delete('/subscription-plans/{plan}',           [SubscriptionController::class, 'destroyPlan']);
    });

    // ─── Categories & Courses (instructor/admin) ──────────────────────────────
    Route::apiResource('categories', CategoryController::class)->except(['index', 'show']);
    Route::apiResource('courses', CourseController::class)->except(['index', 'show']);
    Route::get('/instructor/courses', [CourseController::class, 'myCourses']);
    Route::get('/instructor/courses/trashed', [CourseController::class, 'trashed']);

    // ─── Modules & Lessons (instructor/admin) ─────────────────────────────────
    Route::post('/courses/{course}/modules',  [ModuleController::class, 'store']);
    Route::post('/courses/{id}/restore', [CourseController::class, 'restore']);

    Route::put('/modules/{module}',           [ModuleController::class, 'update']);
    Route::delete('/modules/{module}',        [ModuleController::class, 'destroy']);

    Route::post('/modules/{module}/lessons',  [LessonController::class, 'store']);

    Route::get('/lessons/{lesson}',           [LessonController::class, 'show']);
    Route::put('/lessons/{lesson}',           [LessonController::class, 'update']);
    Route::delete('/lessons/{lesson}',        [LessonController::class, 'destroy']);

    // ─── Enrollments ─────────────────────────────────────────────────────────
    Route::get('/enrollments',                        [EnrollmentController::class, 'index']);
    Route::post('/courses/{course}/enroll',           [EnrollmentController::class, 'store']);
    Route::delete('/enrollments/{enrollment}',        [EnrollmentController::class, 'destroy']);

    // ─── Subscriptions & Payments ─────────────────────────────────────────────
    Route::post('/subscriptions/checkout',            [SubscriptionController::class, 'checkout']);
    Route::get('/subscriptions/me',                   [SubscriptionController::class, 'me']);
    Route::post('/payments/{payment}/confirm',        [SubscriptionController::class, 'confirmPayment']);

    // ─── Progress ────────────────────────────────────────────────────────────
    Route::post('/lessons/{lesson}/complete',         [ProgressController::class, 'markComplete']);
    Route::get('/courses/{course}/progress',          [ProgressController::class, 'courseProgress']);
});