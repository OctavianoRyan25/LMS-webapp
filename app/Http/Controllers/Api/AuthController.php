<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * API Auth Controller — digunakan oleh Flutter app.
 * Menggunakan Laravel Sanctum untuk token-based auth.
 */
final class AuthController extends Controller
{
    /** Login: email + password → kembalikan token */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau kata sandi salah.'],
            ]);
        }

        // Hapus token lama, buat token baru
        $user->tokens()->delete();
        $token = $user->createToken('lms-app')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => [
                'id'         => $user->id,
                'name'       => $user->name,
                'email'      => $user->email,
                'avatar_url' => $user->avatar_url ?? null,
                'role'       => $user->role ? ['name' => $user->role->name] : null,
            ],
        ]);
    }

    /** Registrasi siswa baru */
    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', 'unique:users,email'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            // Default role: cari role 'student', fallback ke null
            'role_id'  => \App\Models\Role::where('name', 'student')->value('id'),
        ]);

        $token = $user->createToken('lms-app')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
            ],
        ], 201);
    }

    /** Logout: hapus token yang sedang digunakan */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Berhasil keluar.']);
    }

    /** Ambil data user yang sedang login */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user()->load('role');

        return response()->json([
            'user' => [
                'id'         => $user->id,
                'name'       => $user->name,
                'email'      => $user->email,
                'avatar_url' => $user->avatar_url ?? null,
                'role'       => $user->role ? ['name' => $user->role->name] : null,
            ],
        ]);
    }
}
