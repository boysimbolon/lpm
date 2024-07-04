<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/files/{folderName}/{fileName}', function ($folderName, $fileName) {
    // Ambil path lengkap dari storage
    $filePath = storage_path('app/public/dokumen/' . $folderName . '/' . $fileName);

    // Periksa apakah file ada
    if (!Storage::exists('public/dokumen/' . $folderName . '/' . $fileName)) {
        return response()->json(['message' => 'File tidak ditemukan'], 404);
    }

    // Dapatkan MIME type dari file
    $mimeType = mime_content_type($filePath);

    // Kembalikan file sebagai response dengan header yang sesuai
    return response()->file($filePath, [
        'Content-Type' => $mimeType,
    ]);
});

