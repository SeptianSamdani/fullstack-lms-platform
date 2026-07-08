<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CategoryPolicy
{
    // Kategori adalah master data — hanya admin yang boleh mengelola
    public function create(User $user): bool { return $user->hasRole('admin'); }
    public function update(User $user): bool { return $user->hasRole('admin'); }
    public function delete(User $user): bool { return $user->hasRole('admin'); }
}