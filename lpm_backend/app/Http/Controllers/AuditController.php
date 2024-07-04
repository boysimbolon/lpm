<?php

namespace App\Http\Controllers;

use App\Models\PerStandars;
use App\Models\Standars;
use Illuminate\Http\Request;
use App\Models\Audits;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isEmpty;


class AuditController extends Controller
{
    public function index()
    {
        $audit = Audits::with([
            'perStandars',
            'users',
            'tipeAudits.fakultas',
        ])->get();
        return response()->json($audit);
    }

    public function show($idAudit,  $id_standars)
    {
        $audit = Audits::with('perStandars', 'users', 'tipeAudits')
            ->where('id_tipe_audits', $idAudit)
            ->whereHas('perStandars', function($query) use ($id_standars) {
                $query->where('id_standars', $id_standars);
            })
            ->get();
        if ($audit->isEmpty()) {
            return response()->json(['message' => 'Anda tidak terpilih mengaudit tersebut'], 404);
        }
        return response()->json(
            $audit
        );
    }

    public function mystatement($idAudit, $id_standars)
    {
        $audit = Audits::with('perStandars', 'users', 'tipeAudits')
            ->where('id_tipe_audits', $idAudit)
            ->whereHas('perStandars', function($query) use ($id_standars) {
                $query->where('id_standars', $id_standars);
            })
            ->get();
        if ($audit->isEmpty()) {
            return response()->json(['message' => 'Anda tidak terpilih mengaudit tersebut'], 404);
        }
        return response()->json(
            $audit
        );
    }

    public function myAudit($idUser)
    {
        $audit = Audits::with('perStandars', 'users', 'tipeAudits')
            ->where('id_users', $idUser)->get();
        if ($audit->isEmpty()) {
            return response()->json(['message' => 'Anda tidak terpilih mengaudit tersebut'], 404);
        }
        return response()->json(
            $audit
        );
    }

    public function DetailAudit($idTipe,$idStandar)
    {
        $audit = Audits::with('perStandars', 'users', 'tipeAudits')
            ->where('id_tipe_audits', $idTipe)
            ->whereHas('perStandars',function ($q) use ($idStandar){
                $q->where('id_standars', $idStandar);
            })->get();
        if ($audit->isEmpty()) {
            return response()->json(['message' => 'Anda tidak terpilih mengaudit tersebut'], 404);
        }
        return response()->json(
            $audit
        );
    }

    public function ViewStandar($idTipe)
    {
        // Mengambil semua audits berdasarkan id_tipe_audits
        $audits = Audits::with('perStandars.standars', 'users', 'tipeAudits')
            ->where('id_tipe_audits', $idTipe)
            ->get();
        // Mengembalikan hasil sebagai array nilai-nilai dari array asosiatif
        return response()->json($audits);
    }

    public function myAuditor($idUser)
    {
        // Jika menggunakan SQL Server
        $audit = Audits::with(['perStandars', 'users' => function ($query) {
            $query->with('fakultas:id,prodi');
        }, 'tipeAudits' => function($query) use ($idUser){
            $query->whereJsonContains('idLPM', $idUser);
        }])
            ->get();

        if ($audit->isEmpty()) {
            return response()->json(['message' => 'Anda tidak terpilih mengaudit tersebut'], 404);
        }

        return response()->json($audit);
    }

    public function kunci($idAudit, $id_standars)
    {
        // Assuming the name of your model is Audit and the table name is audits
        // Update the 'progress' column where the specified conditions match
        $updated = Audits::where('id_tipe_audits', $idAudit)
            ->whereHas('perStandars', function ($query) use($id_standars) {
                $query->where('id_standars', $id_standars);
            })
            ->update(['progress' => '1']);
        if($updated) {
            return response()->json(['message' => 'Progress updated successfully.'], 200);
        } else {
            return response()->json(['message' => 'No rows updated.'], 400);
        }
    }

    public function Buka($idAudit, $idUser, $id_standars)
    {
        // Assuming the name of your model is Audit and the table name is audits
        // Update the 'progress' column where the specified conditions match
        $updated = Audits::where('id_tipe_audits', $idAudit)
            ->where('id_users', $idUser)
            ->whereHas('perStandars', function ($query) use($id_standars) {
                $query->where('id_standars', $id_standars);
            })
            ->update(['progress' => '0']);
        if($updated) {
            return response()->json(['message' => 'Progress updated successfully.'], 200);
        } else {
            return response()->json(['message' => 'No rows updated.'], 400);
        }
    }

    public function AuditStart($idAudit, $idUser, $id_standars)
    {
        // Assuming the name of your model is Audit and the table name is audits
        // Retrieve rows where the specified conditions match
        $results = Audits::with('perStandars')->where('id_tipe_audits', $idAudit)
            ->where('id_users', $idUser)
            ->whereHas('perStandars', function ($query) use ($id_standars) {
                $query->where('id_standars', $id_standars);
            })
            ->get();

        if (!$results->isEmpty()) {
            return response()->json($results, 200);
        } else {
            return response()->json(['message' => 'No matching records found.'], 404);
        }
    }

    public function sent(Request $request)
    {
        try {
            // Ambil data dari request
            $data = $request->only(['id_per_standars', 'id_tipe_audits', 'id_users']);

            // Simpan data ke dalam tabel audit
            Audits::create($data);

            return response()->json(['message' => 'Data berhasil disimpan'], 200);
        } catch (\Exception $e) {
            // Tangani error jika terjadi kesalahan saat menyimpan data
            return response()->json(['message' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        // Validasi data input
        $validatedData = $request->validate([
            'nilai_diri' => 'sometimes|required',
            'dok_pendukung' => 'sometimes|required|file',
            'judul' => 'sometimes|required',
            'name' => 'sometimes|required',
            'pernyataanPendukung' => 'sometimes',
            'feedback'=>'sometimes|required'
        ]);

        // Cari data audit berdasarkan ID
        $audit = Audits::findOrFail($id);
        $existingDokPendukung = json_decode($audit->dok_pendukung, true) ?? [];

        // Simpan perubahan pada data audit
        if ($request->hasFile('dok_pendukung')) {
            $file = $request->file('dok_pendukung');
            $namaFile = $file->getClientOriginalName(); // Dapatkan nama asli file
            $judul = preg_replace('/[^a-zA-Z0-9]/', '-', $request['name']); // Bersihkan nama dengan hanya menyisakan huruf dan angka
            $judulTanpaEkstensi = pathinfo($judul, PATHINFO_FILENAME); // Ambil nama file tanpa ekstensi
            $ekstensi = $file->getClientOriginalExtension(); // Dapatkan ekstensi file

            // Menggabungkan judul dengan ekstensi
            $namaFileBaru = $judulTanpaEkstensi . '.' . $ekstensi;

            $folderName = str_shuffle("0fx" . $id . $judul . "cc");

            // Membuat folder jika belum ada
            $folderPath = 'public/dokumen/' . $folderName;
            if (!Storage::exists($folderPath)) {
                Storage::makeDirectory($folderPath, 0755, true);
            }

            // Simpan file baru dengan nama yang sesuai ke dalam folder yang sesuai
            $file->storeAs($folderPath, $namaFileBaru);

            $existingDokPendukung[] = $folderName . '/' . $namaFileBaru;

            // Update informasi dokumen pendukung
            $audit->dok_pendukung = json_encode($existingDokPendukung);
        }


        // Cek apakah ada nilai_diri yang disertakan dalam request
        if ($request->filled('nilai_diri')) {
            $audit->nilai_diri = $validatedData['nilai_diri'];
        }
        if ($request->filled('pernyataanPendukung')) {
            $audit->catatan = $validatedData['pernyataanPendukung'];
        }
        if ($request->filled('feedback')) {
            $audit->feedback = $validatedData['feedback'];
        }
        // Simpan perubahan data audit
        $audit->save();

        // Memberikan respons JSON jika sukses
        return response()->json(['message' => 'Data berhasil diupdate', 'audit' => $audit], 200);
    }

    public function destroy(Audits $audit)
    {
        try {
            // Mendapatkan path file dokumen pendukung dari database
            $filePath = $audit->dok_pendukung;
            // Menghapus file dokumen pendukung dari storage jika file tersebut ada
            if ($filePath && Storage::exists($filePath)) {
                Storage::delete($filePath);
            }
            // Menghapus data Audit dari database
            $audit->delete();
            return response()->json(['message' => 'Audit berhasil dihapus'], 204);
        } catch (\Exception $e) {
            // Tangani error lainnya jika terjadi kesalahan dalam proses penghapusan
            return response()->json(['message' => 'Gagal menghapus audit: ' . $e->getMessage()], 500);
        }
    }

    public function hapusFile($id, $index)
    {
        try {
            // Mencari record berdasarkan ID
            $find = Audits::find($id);
            // Memastikan record ditemukan
            if ($find) {
                // Mendapatkan array dari kolom 'dok_pendukung'
                $link = $find->dok_pendukung;
                $linkArray = json_decode($link, true);
                // Memastikan 'dok_pendukung' adalah array dan index yang diminta ada dalam array
                if (is_array($linkArray) && array_key_exists($index, $linkArray)) {
                    // Menyimpan path file yang akan dihapus
                    $filePath = 'public/dokumen/' . $linkArray[$index];
                    $parts = explode('/', $linkArray[$index]);
                    // Menghapus elemen dari array
                    unset($linkArray[$index]);
                    if ($filePath && Storage::exists($filePath)) {
                        Storage::delete($filePath);
                    }
                    // Reindex array untuk menghilangkan gap pada indeks
                    $linkArray = array_values($linkArray);

                    // Mengatur kolom 'dok_pendukung' menjadi null jika array kosong
                    if (empty($linkArray)) {
                        $find->dok_pendukung = null;
                    } else {
                        // Mengubah array kembali ke JSON string dan menyimpannya ke kolom 'dok_pendukung'
                        $find->dok_pendukung = json_encode($linkArray);
                    }
                    $find->save(); // Menyimpan perubahan ke database

                    // Memeriksa apakah folder kosong setelah penghapusan file
                    $folderPath = 'public/dokumen/'.$parts[0];
                    if (Storage::exists($folderPath) && count(Storage::files($folderPath)) === 0) {
                        Storage::deleteDirectory($folderPath);
                    }
                }
            }
        } catch (\Exception $e) {
            // Menangani kesalahan dengan logging atau tindakan lain yang sesuai
            \Log::error("Error menghapus file: " . $e->getMessage());
            return response()->json(['error' => 'Gagal menghapus file', 'message' => $e->getMessage()], 500);
        }

        return response()->json(['success' => 'File berhasil dihapus', 'data' => $find]);
    }

    public function batalkan($perStandar)
    {
        try {
            // Mencari audit berdasarkan id_per_standar dan id_user
            $audit = Audits::where('id_per_standars', $perStandar)->first();
            if ($audit) {
                // Mendapatkan path file dokumen pendukung dari database
                $link = $audit->dok_pendukung;
                $linkArray = json_decode($link, true);
                if (is_array($linkArray)) {
                    foreach ($linkArray as $item) {
                        // Mendapatkan path file
                        $parts = explode('/', $item);
                        $filePath = 'public/dokumen/' . $item;
                        // Menghapus file folder pendukung dari storage jika file tersebut ada
                        if (Storage::exists($filePath)) {
                            Storage::delete($filePath);
                            // Memeriksa apakah folder kosong setelah penghapusan file
                            $folderPath = 'public/dokumen/'.$parts[0];
                            if (Storage::exists($folderPath) && count(Storage::files($folderPath)) === 0) {
                                Storage::deleteDirectory($folderPath);
                            }
                        }
                    }
                }
                // Menghapus data Audit dari database
                $audit->delete();

                return response()->json(['message' => 'Audit berhasil dihapus'], 204);
            } else {
                // Jika audit tidak ditemukan, kirimkan respons dengan pesan tidak ditemukan
                return response()->json(['message' => 'Audit tidak ditemukan'], 404);
            }
        } catch (\Exception $e) {
            // Tangani error lainnya jika terjadi kesalahan dalam proses penghapusan
            \Log::error("Error menghapus audit: " . $e->getMessage());
            return response()->json(['message' => 'Gagal menghapus audit: ' . $e->getMessage()], 500);
        }
    }

    public function Allbatalkan($user, $tipe, $standar)
    {
        try {
            // Mencari audit berdasarkan id_tipe_audits, id_users, dan id_standar
            $audits = Audits::whereHas('perstandars', function ($query) use ($standar) {
                $query->whereHas('standars', function ($query) use ($standar) {
                    $query->where('id', $standar);
                });
            })
                ->where('id_tipe_audits', $tipe)
                ->where('id_users', $user)
                ->get();

            if ($audits->isNotEmpty()) {
                foreach ($audits as $audit) {
                    // Mendapatkan path file dokumen pendukung dari database
                    $link = $audit->dok_pendukung;
                    $linkArray = json_decode($link, true);

                    if (is_array($linkArray)) {
                        foreach ($linkArray as $item) {
                            // Mendapatkan path file
                            $parts = explode('/', $item);
                            $filePath = 'public/dokumen/' . $item;

                            // Menghapus file dokumen pendukung dari storage jika file tersebut ada
                            if (Storage::exists($filePath)) {
                                Storage::delete($filePath);

                                // Memeriksa apakah folder kosong setelah penghapusan file
                                $folderPath = 'public/dokumen/' . $parts[0];
                                if (Storage::exists($folderPath) && count(Storage::files($folderPath)) === 0) {
                                    Storage::deleteDirectory($folderPath);
                                }
                            }
                        }
                    }

                    // Menghapus data Audit dari database
                    $audit->delete();
                }

                return response()->json(['message' => 'Audit berhasil dihapus'], 204);
            } else {
                // Jika audit tidak ditemukan, kirimkan respons dengan pesan tidak ditemukan
                return response()->json(['message' => 'Audit tidak ditemukan'], 404);
            }
        } catch (\Exception $e) {
            // Tangani error lainnya jika terjadi kesalahan dalam proses penghapusan
            \Log::error("Error menghapus audit: " . $e->getMessage());
            return response()->json(['message' => 'Gagal menghapus audit: ' . $e->getMessage()], 500);
        }
    }

    public function grafik($Tipe, $tahunA, $tahunB, $sem)
    {
        $periode = "$tahunA/$tahunB";

        // Query menggunakan Query Builder
        $results = DB::table('tb_per_standars')
            ->select(
                'tb_fakultas.prodi',
                'tb_standars.jenis_standar',
                'tb_tipe_audits.periode',
                'tb_tipe_audits.semester',
                'tb_per_standars.poin',
                'tb_audits.nilai_diri'
            )
            ->leftJoin('tb_audits', 'tb_audits.id_per_standars', '=', 'tb_per_standars.id')
            ->leftJoin('tb_users', 'tb_audits.id_users', '=', 'tb_users.id')
            ->leftJoin('tb_standars', 'tb_per_standars.id_standars', '=', 'tb_standars.id')
            ->leftJoin('tb_tipe_audits', 'tb_audits.id_tipe_audits', '=', 'tb_tipe_audits.id')
            ->leftJoin('tb_fakultas', 'tb_tipe_audits.id_fakultas', '=', 'tb_fakultas.id')
            ->where('tb_tipe_audits.tipe_audit', $Tipe)
            ->where('tb_tipe_audits.periode', $periode)
            ->where('tb_tipe_audits.semester', $sem)
            ->get();

        // Mengonversi hasil query ke dalam struktur data yang diinginkan
        $formattedResults = [];
        $standarCounts = [];

        foreach ($results as $result) {
            $jenisStandar = $result->jenis_standar;
            $periode = $result->periode;
            $semester = $result->semester;
            $prodi = $result->prodi;
            $poin = $result->poin;
            $nilaiDiri = $result->nilai_diri;

            if (!isset($formattedResults[$jenisStandar])) {
                $formattedResults[$jenisStandar] = [
                    'jenis_standar' => $jenisStandar,
                    'periode' => $periode,
                    'semester' => $semester,
                    'prodi' => [],
                    'capaian' => []
                ];
                $standarCounts[$jenisStandar] = [];
            }

            if (!isset($standarCounts[$jenisStandar][$prodi])) {
                $standarCounts[$jenisStandar][$prodi] = [
                    'count' => 0,
                    'sum' => 0
                ];
            }

            $standarCounts[$jenisStandar][$prodi]['count']++;
            if ($nilaiDiri >= $poin) {
                $standarCounts[$jenisStandar][$prodi]['sum']++;
            }

            if (!in_array($prodi, $formattedResults[$jenisStandar]['prodi'])) {
                $formattedResults[$jenisStandar]['prodi'][] = $prodi;
            }
        }

        // Hitung capaian berdasarkan standarCounts
        foreach ($formattedResults as $jenisStandar => &$data) {
            foreach ($data['prodi'] as $prodi) {
                $count = $standarCounts[$jenisStandar][$prodi]['count'];
                $sum = $standarCounts[$jenisStandar][$prodi]['sum'];
                $capaian = ($count > 0) ? ($sum / $count) * 100 : 0;
                $data['capaian'][] = round($capaian, 2); // Pembulatan hingga dua angka di belakang koma
            }
        }

        $finalResults = array_values($formattedResults); // Konversi hasil ke array indeks

        return response()->json($finalResults, 201);
    }

    public function grafikAudit($tahun1, $tahun2, $semester)
    {
        $periode = "$tahun1/$tahun2";

        // Query menggunakan Query Builder
        $query = DB::table('tb_audits')
            ->select(
                'tb_fakultas.prodi',
                'tb_per_standars.poin',
                'tb_audits.nilai_diri',
                'tb_tipe_audits.semester',
                'tb_tipe_audits.tipe_audit' // asumsikan 'tipe_audit' adalah kolom yang menunjukkan tipe audit
            )
            ->leftJoin('tb_tipe_audits', 'tb_audits.id_tipe_audits', '=', 'tb_tipe_audits.id')
            ->leftJoin('tb_per_standars', 'tb_audits.id_per_standars', '=', 'tb_per_standars.id')
            ->leftJoin('tb_users', 'tb_audits.id_users', '=', 'tb_users.id')
            ->leftJoin('tb_fakultas', 'tb_tipe_audits.id_fakultas', '=', 'tb_fakultas.id')
            ->where('tb_tipe_audits.periode', $periode);

        if ($semester != "ALL") {
            $query->where('tb_tipe_audits.semester', $semester);
        }

        $results = $query->get();

        // Mengonversi hasil query ke dalam struktur data yang diinginkan
        $formattedResults = [
            'ganjil' => [],
            'genap' => []
        ];

        foreach ($results as $result) {
            $prodi = $result->prodi;
            $nilaiDiri = (float) $result->nilai_diri;
            $poin = (float) $result->poin;
            $semesterResult = strtolower($result->semester); // Mengubah ke huruf kecil untuk konsistensi
            $tipeAudit = $result->tipe_audit;

            if (!isset($formattedResults[$semesterResult][$tipeAudit])) {
                $formattedResults[$semesterResult][$tipeAudit] = [];
            }

            if (!isset($formattedResults[$semesterResult][$tipeAudit][$prodi])) {
                $formattedResults[$semesterResult][$tipeAudit][$prodi] = [
                    'total_statements' => 0,
                    'successful_statements' => 0
                ];
            }

            $formattedResults[$semesterResult][$tipeAudit][$prodi]['total_statements'] += 1;

            if ($nilaiDiri >= $poin) {
                $formattedResults[$semesterResult][$tipeAudit][$prodi]['successful_statements'] += 1;
            }
        }

        // Menghitung persentase keberhasilan
        $finalResults = [
            'periode' => $periode,
            'semester' => [],
            'prodi' => [],
            'poin' => []
        ];

        if ($semester == "ALL") {
            foreach (['ganjil', 'genap'] as $sem) {
                if (isset($formattedResults[$sem])) {
                    $finalResults['semester'][] = $sem;
                    $prodiList = [];
                    $poinList = [];

                    foreach ($formattedResults[$sem] as $tipeAudit => $dataPerProdi) {
                        foreach ($dataPerProdi as $prodi => $data) {
                            $totalStatements = $data['total_statements'];
                            $successfulStatements = $data['successful_statements'];
                            $percentageSuccess = $totalStatements > 0 ? ($successfulStatements / $totalStatements) * 100 : 0;

                            $prodiList[] = $prodi;
                            $poinList[] = number_format($percentageSuccess, 2);
                        }
                    }

                    $finalResults['prodi'][] = $prodiList;
                    $finalResults['poin'][] = $poinList;
                } else {
                    $finalResults['semester'][] = $sem;
                    $finalResults['prodi'][] = [];
                    $finalResults['poin'][] = [];
                }
            }
        } else {
            $finalResults['semester'][] = $semester;
            $prodiList = [];
            $poinList = [];

            foreach ($formattedResults[$semester] as $tipeAudit => $dataPerProdi) {
                foreach ($dataPerProdi as $prodi => $data) {
                    $totalStatements = $data['total_statements'];
                    $successfulStatements = $data['successful_statements'];
                    $percentageSuccess = $totalStatements > 0 ? ($successfulStatements / $totalStatements) * 100 : 0;

                    $prodiList[] = $prodi;
                    $poinList[] = number_format($percentageSuccess, 2);
                }
            }

            $finalResults['prodi'][] = $prodiList;
            $finalResults['poin'][] = $poinList;
        }

        return response()->json($finalResults, 200);
    }

    public function grafikStandar($idTipe)
    {
        // Query menggunakan Query Builder
        $results = DB::table('tb_per_standars')
            ->select(
                'tb_standars.jenis_standar',
                'tb_tipe_audits.periode',
                'tb_tipe_audits.semester',
                'tb_fakultas.prodi',
                DB::raw('ROUND(SUM(CAST(tb_audits.nilai_diri AS FLOAT)), 1) AS total_nilai_diri')
            )
            ->leftJoin('tb_audits', 'tb_audits.id_per_standars', '=', 'tb_per_standars.id')
            ->leftJoin('tb_users', 'tb_audits.id_users', '=', 'tb_users.id')
            ->leftJoin('tb_fakultas', 'tb_users.id_fakultas', '=', 'tb_fakultas.id')
            ->leftJoin('tb_standars', 'tb_per_standars.id_standars', '=', 'tb_standars.id')
            ->leftJoin('tb_tipe_audits', 'tb_audits.id_tipe_audits', '=', 'tb_tipe_audits.id')
            ->where('tb_tipe_audits.tipe_audit', $idTipe)
            ->groupBy('tb_standars.jenis_standar', 'tb_tipe_audits.periode', 'tb_tipe_audits.semester', 'tb_fakultas.prodi')
            ->get();

        // Mengonversi hasil query ke dalam struktur data yang diinginkan
        $formattedResults = [];

        foreach ($results as $result) {
            $jenisStandar = $result->jenis_standar;
            $periode = $result->periode;
            $semester = $result->semester;
            $prodi = $result->prodi;
            $totalNilaiDiri = (float) $result->total_nilai_diri;

            // Inisialisasi jika jenis standar belum ada
            if (!isset($formattedResults[$jenisStandar])) {
                $formattedResults[$jenisStandar] = [
                    'jenis_standar' => $jenisStandar,
                    'periode' => [],
                    'semester' => [],
                    'fakultas' => [],
                    'total_nilai_diri' => []
                ];
            }

            // Menambahkan periode dan semester
            if (!in_array($periode, $formattedResults[$jenisStandar]['periode'])) {
                $formattedResults[$jenisStandar]['periode'][] = $periode;
            }

            if (!in_array($semester, $formattedResults[$jenisStandar]['semester'])) {
                $formattedResults[$jenisStandar]['semester'][] = $semester;
            }

            // Jika prodi belum ada, tambahkan ke dalam array fakultas
            if (!in_array($prodi, $formattedResults[$jenisStandar]['fakultas'])) {
                $formattedResults[$jenisStandar]['fakultas'][] = $prodi;
                // Tambahkan array kosong untuk total_nilai_diri untuk prodi baru
                $formattedResults[$jenisStandar]['total_nilai_diri'][] = array_fill(0, count($formattedResults[$jenisStandar]['semester']), 0.0);
            }

            // Cari indeks fakultas
            $prodiIndex = array_search($prodi, $formattedResults[$jenisStandar]['fakultas']);
            // Cari indeks semester
            $semesterIndex = array_search($semester, $formattedResults[$jenisStandar]['semester']);

            // Tambahkan nilai total_nilai_diri
            $formattedResults[$jenisStandar]['total_nilai_diri'][$prodiIndex][$semesterIndex] = $totalNilaiDiri;
        }

        // Membuat struktur data akhir sesuai dengan format yang diminta
        $finalResults = ['standar' => []];

        foreach ($formattedResults as $jenisStandar => $data) {
            $totalNilaiDiriFormatted = array_fill(0, count($data['fakultas']), []);

            foreach ($data['fakultas'] as $fakultasIndex => $fakultas) {
                foreach ($data['semester'] as $semesterIndex => $semester) {
                    $totalNilaiDiriFormatted[$fakultasIndex][] = $data['total_nilai_diri'][$fakultasIndex][$semesterIndex] ?? 0.0;
                }
            }

            $standarData = [
                'jenis_standar' => $data['jenis_standar'],
                'periode' => $data['periode'],
                'semester' => $data['semester'],
                'fakultas' => $data['fakultas'],
                'total_nilai_diri' => $totalNilaiDiriFormatted
            ];

            $finalResults['standar'][] = $standarData;
        }

        return response()->json($finalResults, 201);
    }
}
