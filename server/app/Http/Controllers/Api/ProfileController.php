<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    /**
     * Tampilkan profile user yang sedang login.
     */
    public function show(Request $request)
    {
        return response()->json($this->withRoles($request->user()));
    }

    /**
     * Update data profile (name, phone, bio).
     * Email sengaja tidak diubah di sini untuk menjaga integritas auth.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'sometimes|string|max:255',
            'phone' => 'nullable|string|max:20',
            'bio'   => 'nullable|string|max:1000',
        ]);

        $user = $request->user();
        $user->update($validated);

        return response()->json($this->withRoles($user));
    }

    /**
     * Upload / ganti foto profile ke Cloudinary.
     */
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|max:2048',
        ]);

        $path = $request->file('avatar')->store('lms/avatars', 'cloudinary');
        $url = Storage::disk('cloudinary')->url($path);

        $user = $request->user();
        $user->update(['avatar_url' => $url]);

        return response()->json($this->withRoles($user));
    }

    /**
     * Update password (dipisahkan dari update profile demi keamanan).
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ]);

        $user = $request->user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Password saat ini salah.',
            ]);
        }

        $user->update(['password' => $validated['password']]);

        return response()->json(['message' => 'Password berhasil diubah.']);
    }

    private function withRoles($user)
    {
        return [
            ...$user->toArray(),
            'roles' => $user->getRoleNames(),
        ];
    }
}