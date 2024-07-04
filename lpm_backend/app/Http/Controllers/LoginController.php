<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // Validasi input dari request
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Mencoba untuk login menggunakan username dan password yang diberikan
        if (!Auth::attempt($request->only('username', 'password'))) {
            // Jika login gagal, lemparkan pengecualian validasi dengan pesan kesalahan
            throw ValidationException::withMessages([
                'username' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Mengambil user berdasarkan username yang diberikan
        $user = Users::where('username', $request->username)->first();

        // Membuat token untuk user yang berhasil login
        $token = $user->createToken('ApiToken')->plainTextToken;

        // Mengembalikan response dalam bentuk JSON dengan informasi user dan token
        return response()->json([
            'Uid' => $user->id,
            'role' => $user->roles->jenis, // Asumsikan 'roles' merupakan relasi dengan model Role
            'name' => $user->name,
            'access_token' => $token,
            'token_type' => 'Bearer',
            'message' => 'Login successful',
        ]);
    }

    public function logout(Request $request)
    {
        // Mendapatkan user yang sedang login
        $user = $request->user();

        if ($user) {
            // Menghapus semua token yang dimiliki oleh user yang sedang login
            $user->tokens()->delete();
            // Mengembalikan response dalam bentuk JSON dengan pesan logout berhasil
            return response()->json([
                'message' => 'Logout successful',
            ]);
        } else {
            // Mengembalikan response dengan pesan kesalahan jika user tidak ditemukan
            return response()->json([
                'message' => 'No authenticated user found.',
            ], 401);
        }
    }
}
