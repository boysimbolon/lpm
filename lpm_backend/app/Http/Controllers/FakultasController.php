<?php

namespace App\Http\Controllers;

use App\Models\Fakultas;
use Illuminate\Http\Request;

class FakultasController extends Controller
{
    public function index()
    {
        $fakultas = Fakultas::orderBy('fakultas', 'asc')->get();
        return response()->json($fakultas);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'fakultas' => 'required|string|max:255',
            'prodi' => 'required|string|max:255',
        ]);
        $fakultas = Fakultas::create($validatedData);
        return response()->json($fakultas, 201);
    }

    public function show(Fakultas $fakultas)
    {
        return response()->json($fakultas);
    }

    public function update(Request $request, Fakultas $fakultas)
    {
        $validatedData = $request->validate([
            'fakultas' => 'sometimes|required|string|max:255',
            'prodi' => 'sometimes|required|string|max:255',
        ]);

        $fakultas->update($validatedData);

        return response()->json($fakultas);
    }

    public function destroy(Fakultas $fakultas)
    {
        $fakultas->delete();

        return response()->json(null, 204);
    }
}
