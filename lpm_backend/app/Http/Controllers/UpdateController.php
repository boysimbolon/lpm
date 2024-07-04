<?php

namespace App\Http\Controllers;

use App\Models\UpdateAudits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UpdateController extends Controller
{
    public function update(Request $request, $files)
    {
        $file = UpdateAudits::findOrFail($files);
        $validatedData = $request->validate([
            'dok_pendukung' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // tambah aturan validasi ukuran file
        ]);

        // Hapus gambar lama jika ada
        $oldFilePath = storage_path('app/public/dok_pendukung').'/'.$file->path;
        if (file_exists($oldFilePath)) {
            unlink($oldFilePath);
        }
        // Buat nama file baru dan pindahkan file yang diupload
        $fileName = time().'.'.$validatedData['dok_pendukung']->extension();
        $validatedData['dok_pendukung']->storeAs('public/dok_pendukung', $fileName);
        // Perbarui informasi gambar di database
        $file->path = $fileName;
        $file->save();
        return response()->json(['message' => 'File updated successfully', 'data' => $file], 200);
    }
}
