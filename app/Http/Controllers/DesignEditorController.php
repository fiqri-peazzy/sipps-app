<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class DesignEditorController extends Controller
{
    /**
     * Upload design image
     */
    public function uploadImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,ai,pdf|max:51200',
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

            // PERBAIKAN: Simpan ke folder TEMPORARY dulu
            // Format: designs/temp/{user_id}/{timestamp}_{filename}
            $userId = Auth::id();
            $timestamp = time();
            $tempPath = $file->store("designs/temp/{$userId}", 'public');

            $url = Storage::url($tempPath);

            return response()->json([
                'success' => true,
                'url' => $url,
                'temp_path' => $tempPath, // Path temporary
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
