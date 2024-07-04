<?php

namespace App\Http\Controllers;

use App\Models\Audits;
use App\Models\PerStandars;
use App\Models\Roles;
use App\Models\Standars;
use App\Models\TipeAudits;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TipeAuditController extends Controller
{
    public function index()
    {
        $tipeAudits = TipeAudits::with('fakultas')->get();
        return response()->json($tipeAudits);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'tipe_audit' => 'required|string|max:255',
            'periode' => 'required|string|max:255',
            'semester' => 'required|string|max:255',
            'id_fakultas' => 'required|exists:tb_fakultas,id',
            'idLPM' => 'sometimes|integer' // Ubah aturan validasi idLPM sesuai kebutuhan
        ]);

        // Validasi idLPM jika ada
        if ($request->has('idLPM')) {
            $idLPM = $request->input('idLPM');
            $idLPMValid = DB::table('tb_users')
                ->where('id', $idLPM)
                ->where('id_roles', 3)
                ->exists();

            if (!$idLPMValid) {
                return response()->json(['error' => 'Invalid idLPM'], 422);
            }
        }

        // Memastikan 'idLPM' disimpan sebagai array integer dalam bentuk JSON
        if (isset($validatedData['idLPM'])) {
            if (!is_array($validatedData['idLPM'])) {
                $validatedData['idLPM'] = [(int)$validatedData['idLPM']];
            } else {
                $validatedData['idLPM'] = array_map('intval', $validatedData['idLPM']);
            }

            $validatedData['idLPM'] = json_encode($validatedData['idLPM']);
        }

        $tipeAudit = TipeAudits::create($validatedData);
        return response()->json($tipeAudit, 201);
    }


    public function assign(Request $request)
    {
        $validatedData = $request->validate([
            'idLPM' => 'required',
            'id' => 'required|exists:tb_tipe_audits,id',
        ]);
        $tipeAudit = TipeAudits::findOrFail($validatedData['id']);
        // Mengambil array idLPM yang sudah ada, atau membuat array baru jika belum ada
        $idLPMArray = json_decode($tipeAudit->idLPM, true);
        if (!is_array($idLPMArray)) {
            $idLPMArray = [];
        }

        // Menambahkan idLPM baru ke dalam array
        $idLPMArray[] = $validatedData['idLPM'];

        // Menghapus duplikat dan mengembalikan nilai baru untuk idLPM
        $tipeAudit->idLPM = json_encode(array_unique($idLPMArray));

        // Simpan perubahan data audit
        $tipeAudit->save();

        // Memberikan respons JSON jika sukses
        return response()->json(['message' => 'Data berhasil diupdate', 'audit' => $tipeAudit], 200);
    }

    public function batalAssign(Request $request){
        $validateData = $request->validate([
            'id' => 'required',
            'idLPM' => 'required'
        ]);
        $tipeAudit = TipeAudits::findOrFail($validateData['id']);
        // Mengambil array idLPM yang sudah ada
        $idLPMArray = json_decode($tipeAudit->idLPM, true) ?? [];
        // Menghapus idLPM dari array jika ada
        $idLPMArray = array_values(array_diff($idLPMArray, [$validateData['idLPM']]));
        // Jika array kosong, diset menjadi null, jika tidak, disimpan kembali sebagai JSON
        $tipeAudit->idLPM = count($idLPMArray) > 0 ? json_encode($idLPMArray) : null;
        // Simpan perubahan data audit
        $tipeAudit->save();
        // Memberikan respons JSON jika sukses
        return response()->json(['message' => 'Data berhasil diupdate', 'audit' => $tipeAudit], 200);
    }

    public function salin(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'tipe_audit' => 'required|string|max:255',
            'periode' => 'required|string|max:255',
            'semester' => 'required|string|max:255',
            'id_fakultas' => 'required|exists:tb_fakultas,id',
            'idLPM' => 'sometimes|integer|nullable',
            'tipe_audit_sebelumnya' => 'required|exists:tb_tipe_audits,id' // Pastikan tipe_audit_sebelumnya ada
        ]);

        if ($request->has('idLPM')) {
            $idLPM = $request->input('idLPM');
            $idLPMValid = DB::table('tb_users')
                ->where('id', $idLPM)
                ->where('id_roles', 3)
                ->exists();

            if (!$idLPMValid) {
                return response()->json(['error' => 'Invalid idLPM'], 422);
            }
        }

        // Memastikan 'idLPM' disimpan sebagai array integer dalam bentuk JSON
        if (isset($validatedData['idLPM'])) {
            if (!is_array($validatedData['idLPM'])) {
                $validatedData['idLPM'] = [(int)$validatedData['idLPM']];
            } else {
                $validatedData['idLPM'] = array_map('intval', $validatedData['idLPM']);
            }

            $validatedData['idLPM'] = json_encode($validatedData['idLPM']);
        }


        // Buat entri baru untuk tipe_audit
        $tipeAudit = TipeAudits::create($validatedData);

        // Ambil data per_standars berdasarkan tipe_audit sebelumnya
        $perStandars = PerStandars::where('id_tipe_audits', $validatedData['tipe_audit_sebelumnya'])->get();

        // Ambil id_standars unik dari per_standars
        $uniqueStandarsIds = $perStandars->pluck('id_standars')->unique();

        // Salin data standars dan per_standars
        foreach ($uniqueStandarsIds as $idStandarLama) {
            // Ambil data standar lama dari tb_standars
            $standarLama = Standars::find($idStandarLama);

            if ($standarLama) {
                // Duplikasi data standar lama ke entri standar baru
                $standarBaru = $standarLama->replicate();
                $standarBaru->save();

                // Ambil ID dari standar baru
                $idStandarBaru = $standarBaru->id;

                // Ambil semua data per_standars yang sesuai dengan id_standars lama dan id_tipe_audits
                $perStandarsToDuplicate = PerStandars::where('id_standars', $idStandarLama)
                    ->where('id_tipe_audits', $validatedData['tipe_audit_sebelumnya'])
                    ->get();

                // Salin data per_standars ke tipe_audit yang baru dengan id_standars baru
                foreach ($perStandarsToDuplicate as $perStandar) {
                    $perStandarAttributes = $perStandar->getAttributes(); // Ambil seluruh atribut dari model PerStandars
                    unset($perStandarAttributes['id']); // Hapus ID agar tidak ada konflik dengan primary key
                    $perStandarAttributes['id_standars'] = $idStandarBaru; // Tetapkan id_standars baru
                    $perStandarAttributes['id_tipe_audits'] = $tipeAudit->id; // Tetapkan id_tipe_audits baru

                    // Buat entri per_standars baru
                    PerStandars::create($perStandarAttributes);
                }
            }
        }

        // Kembalikan response JSON
        return response()->json($tipeAudit, 201);
    }

    public function show(TipeAudits $tipeAudit)
    {
        return response()->json($tipeAudit);
    }
    public function MyTipe($idlpm)
    {
        // Jika menggunakan SQL Server
        $tipeAudits = TipeAudits::with('fakultas')
            ->get()
            ->filter(function ($item) use ($idlpm) {
                $idLPMArray = json_decode($item->idLPM);
                // Pastikan $idLPMArray adalah array sebelum memanggil in_array
                if (is_array($idLPMArray)) {
                    return in_array($idlpm, $idLPMArray);
                }
                return false;
            })
            ->values(); // Reset the array keys to be consecutive

        // Menggunakan response()->json() untuk mengembalikan response JSON
        return response()->json($tipeAudits);
    }

    public function MyTipes($idlpm)
    {
        // Mengambil data tipeAudits yang unik dari Audits
        $uniqueTipeAudits = Audits::with('perStandars', 'tipeAudits.fakultas')
            ->where('id_users', $idlpm)
            ->get()
            ->pluck('tipeAudits') // Mengambil relasi tipeAudits
            ->flatten() // Menggabungkan koleksi tipeAudits
            ->unique('id') // Mengambil hanya yang unik berdasarkan 'id'
            ->values(); // Mereset kunci indeks

        // Menggunakan response()->json() untuk mengembalikan response JSON
        return response()->json($uniqueTipeAudits);
    }

    public function update(Request $request, TipeAudits $tipeAudit)
    {
        $validatedData = $request->validate([
            'tipe_audit' => 'sometimes|required|string|max:255',
            'id_fakultas' => 'sometimes|required|exists:tb_fakultas,id',
            'periode' => 'sometimes|required|string|max:255',
            'semester' => 'sometimes|required|string|max:255',
        ]);
        $tipeAudit->update($validatedData);
        return response()->json($tipeAudit);
    }
    public function verify(Request $request){
        // Menggunakan metode validate untuk memverifikasi apakah nilai 'code' adalah 'UNAIBisa123'
        $verify = $request->validate([
            'code' => 'required|string|in:UNAIBisa123',
        ]);
        // Jika validasi berhasil, return true
        return response()->json(['valid' => true], 200);
        // Jika validasi gagal, return false
         return response()->json(['valid' => false], 400);
    }

    public function destroy(TipeAudits $tipeAudit)
    {
        try {
            // Hapus semua data terkait di tb_per_standars
            PerStandars::where('id_tipe_audits', $tipeAudit->id)->delete();
            // Hapus data di tb_tipe_audits
            $tipeAudit->delete();
            return response()->json(['message' => 'Data deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete data', 'error' => $e->getMessage()], 500);
        }
    }
}
