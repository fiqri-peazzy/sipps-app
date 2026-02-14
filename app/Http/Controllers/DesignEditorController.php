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
     * Show Standalone Design Editor Page
     */
    public function index($index)
    {
        $state = session('place_order_form_state');

        // Jika tidak ada state di session, kemungkinan user akses langsung.
        // Kita bisa ambil default atau arahkan balik.
        if (!$state || !isset($state['orderItems'][$index])) {
            return redirect()->route('customer.order.create');
        }

        $item = $state['orderItems'][$index];

        return view('customer.design-editor.index', [
            'itemIndex' => $index,
            'item' => $item,
            'existingConfig' => $item['design_config'] ?? null,
        ]);
    }

    /**
     * Save Design to Session
     */
    public function saveDesign(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_index' => 'required',
            'design_config' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Data tidak valid']);
        }

        $index = $request->item_index;
        $config = $request->design_config;

        $state = session('place_order_form_state');
        if ($state && isset($state['orderItems'][$index])) {
            $state['orderItems'][$index]['design_config'] = $config;
            if (isset($config['warna_kaos'])) {
                $state['orderItems'][$index]['warna_kaos'] = $config['warna_kaos'];
            }
            session(['place_order_form_state' => $state]);
        }

        // ALWAYS sync with standalone design session as backup
        $sessionKey = 'order_designs_' . Auth::id();
        $sessionData = session($sessionKey, []);
        $sessionData[$index] = $config;
        session([$sessionKey => $sessionData]);

        return response()->json(['success' => true]);
    }
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
     * Upload design snapshot (preview image)
     */
    public function uploadSnapshot(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|string', // Base64 string
            'area' => 'required|in:front,back,left_sleeve,right_sleeve',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $userId = Auth::id();
            $imageData = $request->image;
            $area = $request->area;

            // Remove header if exists
            if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
                $imageData = substr($imageData, strpos($imageData, ',') + 1);
                $type = strtolower($type[1]); // jpg, png, etc
            } else {
                return response()->json(['success' => false, 'message' => 'Format gambar tidak valid'], 422);
            }

            $imageData = base64_decode($imageData);

            if ($imageData === false) {
                return response()->json(['success' => false, 'message' => 'Gagal decode gambar'], 422);
            }

            $fileName = "snapshot_{$area}_" . time() . ".{$type}";
            $tempPath = "designs/temp/{$userId}/snapshots/{$fileName}";

            Storage::disk('public')->put($tempPath, $imageData);
            $url = Storage::url($tempPath);

            return response()->json([
                'success' => true,
                'url' => $url,
                'temp_path' => $tempPath,
                'area' => $area
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal simpan snapshot: ' . $e->getMessage()
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
