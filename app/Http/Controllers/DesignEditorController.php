<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DesignEditorController extends Controller
{
    /**
     * Upload design image
     */
    public function uploadImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,ai,pdf|max:51200', // 50MB untuk file mentah
            'area' => 'required|in:front,back,left_sleeve,right_sleeve',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $file = $request->file('image');

            // Info file original
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $fileSize = $file->getSize();

            // Simpan file ORIGINAL (high-res) untuk produksi
            $originalPath = $file->store('designs/originals', 'public');

            // Generate thumbnail untuk canvas preview (optional - kalau file terlalu besar)
            // Ini opsional, bisa langsung pakai original juga
            $previewPath = $originalPath; // Untuk sementara pakai original

            $url = Storage::url($originalPath);

            return response()->json([
                'success' => true,
                'url' => $url,
                'original_path' => $originalPath,
                'preview_path' => $previewPath,
                'original_name' => $originalName,
                'file_size' => $fileSize,
                'extension' => $extension,
                'area' => $request->area
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal upload gambar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete temporary design image
     */
    public function deleteImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'path' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            if (Storage::disk('public')->exists($request->path)) {
                Storage::disk('public')->delete($request->path);
            }

            return response()->json([
                'success' => true,
                'message' => 'Gambar berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal hapus gambar: ' . $e->getMessage()
            ], 500);
        }
    }
}
