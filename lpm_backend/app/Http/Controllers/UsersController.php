<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function index()
    {
        $users = Users::with('Fakultas','Roles')->get();
        return response()->json($users);
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:tb_users|string|max:50',
            'password' => 'required|string|max:250',
            'name' => 'required|string|max:50',
            'id_fakultas' => 'nullable|exists:tb_fakultas,id',  // Menggunakan nullable untuk memungkinkan null value
            'id_roles' => 'required|exists:tb_roles,id',
        ]);

        $user = new Users();
        $user->username = $request->input('username');
        $user->id_fakultas = $request->input('id_fakultas', null);  // Default ke null jika tidak ada input
        $user->id_roles = $request->input('id_roles');
        $user->name = $request->input('name');
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return response()->json($user, 201);
    }

    public function show($fakultas)
    {
        $user=Users::with(['Fakultas','Roles'])
        ->whereHas('fakultas',function($q) use ($fakultas){
            $q->where('fakultas',$fakultas);
        })->get();
        return response()->json($user);
    }

    public function userAuditor()
    {
        $user=Users::with(['Fakultas','Roles'])
            ->whereHas('roles',function($q){
                $q->where('jenis',"auditor");
            })->get();
        return response()->json($user);
    }

    public function update(Request $request, Users $user)
    {
        $request->validate([
            'username' => 'sometimes|required|unique:tb_users,username,' . $user->id . '|string|max:50',
            'password' => 'sometimes|required|string|max:250',
            'id_fakultas' => 'nullable|exists:tb_fakultas,id',
            'id_roles' => 'sometimes|required|exists:tb_roles,id',
        ]);

        // Update username only if it's different from the current one
        if ($request->has('username') && $request->input('username') !== $user->username) {
            $user->username = $request->input('username');
        }

        // Update password only if it's provided
        if ($request->has('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        // Update other fields
        if ($request->has('id_fakultas')) {
            $user->id_fakultas = $request->input('id_fakultas');
        }
        if ($request->has('id_roles')) {
            $user->id_roles = $request->input('id_roles');
        }

        // Save the user with the updated data
        $user->save();

        return response()->json($user);
    }

    public function MyUpdate(Request $request, Users $user)
    {
        // Validasi input
        $request->validate([
            'username' => 'required|string|max:255', // Hapus validasi unique
            'passwordLama' => 'required_with:passwordBaru|string|max:250',
            'passwordBaru' => 'sometimes|required_with:passwordLama|string|max:250'
        ]);

        // Periksa apakah inputan username sama dengan username yang ada di database
        if ($request->input('username') !== $user->username) {
            return response()->json(['error' => 'Username tidak dapat diubah.'], 400);
        }

        // Update username (tidak perlu melakukan apapun)

        // Verifikasi password lama sebelum mengganti dengan password baru
        if ($request->filled('passwordLama') && $request->filled('passwordBaru')) {
            if (Hash::check($request->input('passwordLama'), $user->password)) {
                // Hash dan simpan password baru
                $user->password = Hash::make($request->input('passwordBaru'));
            } else {
                // Jika password lama tidak cocok, kembalikan respon error
                return response()->json(['error' => 'Password lama tidak sesuai.'], 400);
            }
        }

        // Simpan perubahan
        $user->save();

        // Kembalikan respon berhasil
        return response()->json($user);
    }

    public function destroy(Users $user)
    {
        $user->delete();

        return response()->json(null, 204);
    }
}

