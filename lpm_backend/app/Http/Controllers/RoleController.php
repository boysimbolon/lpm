<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Roles::all();
        return response()->json($roles);
    }

    public function show(Roles $role)
    {
        return response()->json($role);
    }

    public function update(Request $request, Roles $role)
    {
        $validatedData = $request->validate([
            'jenis' => 'sometimes|required|in:admin,auditee,auditor',
        ]);

        $role->update($validatedData);

        return response()->json($role);
    }

    public function destroy(Roles $role)
    {
        $role->delete();

        return response()->json(null, 204);
    }
}

