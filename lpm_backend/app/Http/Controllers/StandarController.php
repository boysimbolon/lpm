<?php

namespace App\Http\Controllers;

use App\Models\PerStandars;
use App\Models\Standars;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\BinaryOp\NotEqual;

class StandarController extends Controller
{
    public function index()
    {
        $standars = Standars::all();
        return response()->json($standars);
    }

    public function store(Request $request)
    {
        // Validasi data
        $validatedData = $request->validate([
            'jenis_standar' => 'required|string|max:50',
            'idtipe' => 'required|integer',
        ]);
        try {
            // Simpan data ke tabel Standars
            $standar = Standars::create([
                'jenis_standar' => $validatedData['jenis_standar']
            ]);
            // Simpan data ke tabel RelatedTable
            PerStandars::create([
                'id_standars' => $standar->id,
                'id_tipe_audits' => $validatedData['idtipe'],
                'pernyataan_standar'=> "0",
                'poin' =>"0"
            ]);
            // Kembalikan response sukses
            return response()->json($standar, 201);
        } catch (\Exception $e) {
            // Kembalikan response error
            return response()->json(['error' => 'Failed to create data'], 500);
        }
    }

    public function show(Standars $standar)
    {
        return response()->json($standar);
    }

    public function update(Request $request, Standars $standar)
    {
        // Validasi data yang diterima
        $validatedData = $request->validate([
            'jenis_standar' => 'sometimes|required|string|max:50',
            'id_tipe_audits' => 'sometimes|exists:tb_tipe_audits,id',
        ]);

        // Pisahkan data yang sudah divalidasi
        $standarData = $request->only('jenis_standar');
        $perStandarsData = $request->only('id_tipe_audits');

        // Update data Standars jika ada
        if (!empty($standarData)) {
            $standar->update($standarData);
        }

        if (!empty($perStandarsData)) {
            // Cek apakah ada entri PerStandars dengan id_standars yang sama
            $existingPerStandars = PerStandars::where('id_standars', $standar->id)->get();

            // Duplikasi data Standars yang sudah diedit atau data lama jika tidak diedit
            $newStandar = $standar->replicate();
            $newStandar->save();

            if ($existingPerStandars->isNotEmpty()) {
                // Jika ada entri PerStandars yang ada, gunakan nilai yang ada
                foreach ($existingPerStandars as $existingPerStandar) {
                    // Buat entri baru di PerStandars dengan ID standars yang baru dan ID tipe audits yang diarahkan
                    PerStandars::create([
                        'id_standars' => $newStandar->id,
                        'id_tipe_audits' => $perStandarsData['id_tipe_audits'],
                        'pernyataan_standar' => $existingPerStandar->pernyataan_standar,
                        'poin' => $existingPerStandar->poin,
                    ]);
                }
            } else {
                // Jika tidak ada entri PerStandars, tambahkan entri baru dengan nilai default 0 0
                PerStandars::create([
                    'id_standars' => $newStandar->id,
                    'id_tipe_audits' => $perStandarsData['id_tipe_audits'],
                    'pernyataan_standar' => 0, // Nilai default
                    'poin' => 0, // Nilai default
                ]);
            }
        }

        // Kembalikan respons dalam bentuk JSON
        return response()->json($standar);
    }

    public function destroy(Standars $standar)
    {
        $standar->delete();
        return response()->json(null, 204);
    }
}

