<?php

namespace App\Http\Controllers;

use App\Models\Audits;
use App\Models\PerStandars;
use Illuminate\Http\Request;

class PerStandarController extends Controller
{
    public function index()
    {
        $perStandars = PerStandars::with('TipeAudits','Standars')->get();
        return response()->json($perStandars);
    }

    public function view()
    {
        // Mengambil data perStandars dengan relasi TipeAudits dan Standars
        $perStandars = PerStandars::with('TipeAudits', 'Standars')->get();

        // Mengelompokkan data berdasarkan id_standars dan jenis_standar
        $groupedData = [];

        foreach ($perStandars as $item) {
            // Pastikan relasi Standars ada
            if ($item->standars) {
                $standarId = $item->standars->id;
                $standarJenis = $item->standars->jenis_standar;

                if (!isset($groupedData[$standarId])) {
                    $groupedData[$standarId] = [
                        'id_standars' => $standarId,
                        'jenis_standar' => $standarJenis,
                        'tipe_audits' => []
                    ];
                }
                // Pastikan relasi TipeAudits ada
                if ($item->tipeAudits) {
                    // Mencari tipe audit yang sudah ada dalam groupedData untuk menghindari duplikasi
                    $auditExists = false;
                    foreach ($groupedData[$standarId]['tipe_audits'] as $audit) {
                        if ($audit['id_tipe_audits'] == $item->tipeAudits->id) {
                            $auditExists = true;
                            break;
                        }
                    }

                    // Jika tipe audit belum ada dalam groupedData, tambahkan
                    if (!$auditExists) {
                        $groupedData[$standarId]['tipe_audits'][] = [
                            'id_tipe_audits' => $item->tipeAudits->id,
                            'tipe_audit' => $item->tipeAudits->tipe_audit,
                            'periode' => $item->tipeAudits->periode,
                            'semester' => $item->tipeAudits->semester
                        ];
                    }
                }
            }
        }

        // Mengubah array asosiatif menjadi array numerik
        $responseData = array_values($groupedData);

        // Mengembalikan respons JSON
        return response()->json($responseData);
    }

    public function store(Request $request)
    {
        try {
            // Validasi data input
            $validatedData = $request->validate([
                'id_standars' => 'required|exists:tb_standars,id',
                'id_tipe_audits' => 'required|exists:tb_tipe_audits,id',
                'pernyataan_standar' => 'required|string|max:400',
                'poin' => ['required', 'numeric', 'regex:/^\d+(\.\d+)?$/'],
                'id_users' => 'required|integer'
            ]);

            // Membuat record baru berdasarkan data yang divalidasi
            $perStandar = PerStandars::create($validatedData);

            // Membuat entri audit (Audits) setelah berhasil membuat PerStandars
            $auditData = [
                'id_users' => $validatedData['id_users'],
                'id_per_standars' => $perStandar->id,
                'id_tipe_audits' => $validatedData['id_tipe_audits'],
                'nilai_diri',
                'dok_pendukung'
            ];
            $audit = Audits::create($auditData);
            // Memberikan respons dengan kode status 201 (Created) dan data yang berhasil dibuat
            return response()->json([
                'perStandar' => $perStandar,
                'audit' => $audit
            ], 201);
        } catch (\Exception $e) {
            // Tangani error lainnya jika terjadi kesalahan dalam proses penyimpanan
            return response()->json(['message' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        }
    }

    public function Input(Request $request)
    {
        try {
            // Validasi data input
            $validatedData = $request->validate([
                'id_standars' => 'required|exists:tb_standars,id',
                'id_tipe_audits' => 'required|exists:tb_tipe_audits,id',
                'pernyataan_standar' => 'required|string|max:400',
                'poin' => ['required', 'numeric', 'regex:/^\d+(\.\d+)?$/'],
            ]);

            // Membuat record baru berdasarkan data yang divalidasi
            $perStandar = PerStandars::create($validatedData);

            return response()->json([
                'perStandar' => $perStandar
            ], 201);
        } catch (\Exception $e) {
            // Tangani error lainnya jika terjadi kesalahan dalam proses penyimpanan
            return response()->json(['message' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        }
    }

    public function show($tipeAudit, $standars)
    {
        $audit = PerStandars::with(['TipeAudits','Standars'])
            ->where('id_standars',$standars)
            ->where('id_tipe_audits',$tipeAudit)->get();
        if (!$audit) {
            return response()->json(['message' => 'Audit not found'], 404);
        }
        return response()->json($audit);
    }

    public function Standars($idTipeAudit) {
        // Ambil data PerStandars yang terkait dengan id_tipe_audits yang diberikan
        $PerStandar = PerStandars::with('Standars')->where('id_tipe_audits', $idTipeAudit)->get();

        // Jika tidak ada data ditemukan, kembalikan response not found
        if ($PerStandar->isEmpty()) {
            return response()->json(['message' => 'Tipe audits not found'], 404);
        }

        // Array untuk menyimpan ID standar yang unik
        $uniqueStandardIds = [];

        // Array untuk menyimpan data standar yang unik beserta status
        $standards = [];

        // Iterasi setiap PerStandar untuk memeriksa status progress
        foreach ($PerStandar as $perstandar) {
            $audits = Audits::with('perStandars')
                ->whereHas('perStandars', function ($query) use ($perstandar) {
                    $query->where('id_standars', $perstandar->Standars->id);
                })
                ->get();

            // Periksa jika perstandar ada dalam audits
            if ($audits->isNotEmpty()) {
                // Periksa jika semua kolom progress pada audits bernilai 1
                $allProgressOne = $audits->every(function ($audit) {
                    return $audit->progress == 1;
                });

                // Ubah status berdasarkan hasil pengecekan progress
                $status = $allProgressOne ? true : false;
            } else {
                // Jika perstandar tidak ada dalam audits, tetapkan status 'belum'
                $status = 'belum';
            }

            // Tambahkan ID standar ke dalam array $uniqueStandardIds jika belum ada
            if (!in_array($perstandar->Standars->id, $uniqueStandardIds)) {
                $uniqueStandardIds[] = $perstandar->Standars->id;

                // Tambahkan data standar ke dalam array $standards
                $standards[] = [
                    'id' => $perstandar->Standars->id,
                    'jenis_standar' => $perstandar->Standars->jenis_standar,
                    'status' => $status,
                ];
            }
        }

        // Kembalikan response dengan data standar yang terstruktur
        return response()->json($standards);
    }

    public function myStandar($idLPM)
    {
        // Ambil data PerStandars yang memiliki relasi TipeAudits dan Standars
        $audit = PerStandars::with(['TipeAudits', 'Standars'])
            ->get()
            ->filter(function ($item) use ($idLPM) {
                // Decode idLPM to an array, or set it to an empty array if it's null
                $idLPMArray = json_decode($item->TipeAudits->idLPM, true) ?? [];
                return in_array($idLPM, $idLPMArray);
            })
            ->map(function ($item) {
                // Mapping data untuk menciptakan format yang diinginkan
                return [
                    'id' => $item->id,
                    'id_standars' => $item->id_standars,
                    'id_tipe_audits' => $item->id_tipe_audits,
                    'pernyataan_standar' => $item->pernyataan_standar,
                    'poin' => $item->poin,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                    'tipe_audits' => [
                        'id' => $item->TipeAudits->id,
                        'tipe_audit' => $item->TipeAudits->tipe_audit,
                        'periode' => $item->TipeAudits->periode,
                        'idLPM' => $item->TipeAudits->idLPM,
                        'created_at' => $item->TipeAudits->created_at,
                        'updated_at' => $item->TipeAudits->updated_at,
                    ],
                    'standars' => [
                        'id' => $item->Standars->id,
                        'jenis_standar' => $item->Standars->jenis_standar,
                        'created_at' => $item->Standars->created_at,
                        'updated_at' => $item->Standars->updated_at,
                    ],
                ];
            });

        // Jika tidak ada data yang ditemukan, kembalikan response 404
        if ($audit->isEmpty()) {
            return response()->json(['message' => 'Audit not found'], 404);
        }

        // Kembalikan response JSON dengan data yang telah diformat
        return response()->json(array_values($audit->toArray()));
    }

    public function my_standars($idUser, $idTipe)
    {
        $standar = Audits::with('perStandars.standars')
            ->where('id_users', $idUser)
            ->where('id_tipe_audits', $idTipe)
            ->get()
            ->pluck('perStandars.standars')
            ->flatten()
            ->unique('id')
            ->values();

        return response()->json($standar);
    }

    public function update(Request $request, PerStandars $perStandar)
    {
        $validatedData = $request->validate([
            'pernyataan_standar' => 'required|string|max:400',
            'poin' => ['required', 'numeric', 'regex:/^\d+(\.\d+)?$/'],
        ]);
        $perStandar->update($validatedData);
        return response()->json($perStandar);
    }

    public function destroy(PerStandars $perStandar)
    {
        $perStandar->delete();

        return response()->json(null, 204);
    }
}
