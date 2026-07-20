<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    /**
     * Daftar semua user (untuk keperluan admin).
     */
    public function users(Request $request)
    {
        return response()->json(
            User::with('roles')
                ->when($request->search, fn($q, $s) => $q->where('name', 'like', "%{$s}%")
                    ->orWhere('email', 'like', "%{$s}%"))
                ->paginate(20)
        );
    }

    /**
     * Assign role ke user tertentu.
     * Hanya admin yang bisa mengubah role user.
     *
     * Body: { "role": "instructor" }
     * Role yang tersedia: student, instructor, admin
     */
    public function assignRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|string|exists:roles,name',
        ]);

        // Cegah admin mengubah role akun sendiri (hindari kehilangan akses tidak sengaja)
        if ($user->id === $request->user()->id) {
            return response()->json([
                'message' => 'Tidak bisa mengubah role akun Anda sendiri. Minta admin lain untuk melakukannya.',
            ], 422);
        }

        // Cegah admin terakhir di-demote sehingga sistem kehilangan admin sama sekali
        if ($user->hasRole('admin') && $validated['role'] !== 'admin') {
            $adminCount = User::role('admin')->count();
            if ($adminCount <= 1) {
                return response()->json([
                    'message' => 'Tidak bisa mengubah role admin terakhir yang tersisa di sistem.',
                ], 422);
            }
        }

        // Hapus semua role lama lalu assign role baru
        $user->syncRoles([$validated['role']]);

        return response()->json([
            'message' => "Role '{$validated['role']}' berhasil di-assign ke {$user->name}.",
            'user'    => $user->load('roles'),
        ]);
    }

    /**
     * Daftar semua role yang tersedia.
     */
    public function roles()
    {
        return response()->json(Role::select('id', 'name')->get());
    }
}
