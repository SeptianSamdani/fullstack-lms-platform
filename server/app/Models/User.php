<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    protected $fillable = ['name', 'email', 'password'];
    protected $hidden = ['password', 'remember_token'];

    public function isAdmin(): bool 
    { 
        return $this->hasRole('admin'); 
    }

    public function isInstructor(): bool 
    { 
        return $this->hasRole('instructor'); 
    }

    public function isStudent(): bool 
    { 
        return $this->hasRole('student');
    }

    public function enrollments(): HasMany
    { 
        return $this->hasMany(Enrollment::class); 
    }

    public function subscriptions(): HasMany 
    { 
        return $this->hasMany(Subscription::class); 
    }

    public function hasActiveSubscription(): bool
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->where('end_date', '>=', now())
            ->exists();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
