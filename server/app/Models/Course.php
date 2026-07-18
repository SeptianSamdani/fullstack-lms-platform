<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    /** @use HasFactory<\Database\Factories\CourseFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ['title', 'description', 'instructor_id', 'category_id', 'type', 'price', 'status', 'thumbnail_url'];

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }   

    public function modules(): HasMany
    {
        return $this->hasMany(Module::class);
    }

    public function enrollments(): HasMany 
    { 
        return $this->hasMany(Enrollment::class); 
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Cek apakah $user berhak melihat konten lesson (content_url & content) course ini:
     * - Owner (instructor pemilik course) atau admin → selalu boleh
     * - Course type 'free' → siapa saja boleh, termasuk guest (user null)
     * - Course type 'paid' → hanya user yang sudah enroll di course ini
     */
    public function isContentAccessibleBy(?User $user): bool
    {
        if ($this->type !== 'paid') {
            return true;
        }

        if (!$user) {
            return false;
        }

        if ($user->id === $this->instructor_id || $user->hasRole('admin')) {
            return true;
        }

        return $this->enrollments()->where('user_id', $user->id)->exists();
    }
}
