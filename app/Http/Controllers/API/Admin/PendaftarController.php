<?php

namespace App\Http\Controllers\API\Admin;

use App\Models\Formulir;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PendaftarController extends Controller
{
    /**
     * Display a listing of registrants
     */
    public function index(Request $request): JsonResponse
    {
        $query = Formulir::with(['user', 'programStudi', 'tahunAjaran']);

        // Filter by tahun ajaran
        if ($request->has('tahun_ajaran_id')) {
            $query->where('tahun_ajaran_id', $request->tahun_ajaran_id);
        }

        // Filter by program studi
        if ($request->has('program_studi_id')) {
            $query->where('program_studi_id', $request->program_studi_id);
        }

        // Filter by status
        if ($request->has('status_verifikasi')) {
            $query->where('status_verifikasi', $request->status_verifikasi);
        }

        // Sort by created_at by default
        $query->orderBy('created_at', 'desc');

        $pendaftar = $query->paginate(10);

        return response()->json([
            'data' => $pendaftar,
            'meta' => [
                'total' => $pendaftar->total(),
                'per_page' => $pendaftar->perPage(),
                'current_page' => $pendaftar->currentPage(),
                'last_page' => $pendaftar->lastPage(),
            ]
        ]);
    }

    /**
     * Display the specified registrant
     */
    public function show(string $id): JsonResponse
    {
        $formulir = Formulir::with(['user', 'programStudi', 'tahunAjaran'])
            ->findOrFail($id);

        return response()->json([
            'data' => $formulir
        ]);
    }

    /**
     * Update registration verification status
     */
    public function verifikasi(Request $request, string $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status_verifikasi' => 'required|in:verified,rejected',
            'catatan_verifikasi' => 'required_if:status_verifikasi,rejected|string|nullable'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $formulir = Formulir::findOrFail($id);
        
        $formulir->update([
            'status_verifikasi' => $request->status_verifikasi,
            'catatan_verifikasi' => $request->catatan_verifikasi
        ]);

        return response()->json([
            'message' => 'Registration status updated successfully',
            'data' => $formulir
        ]);
    }

    /**
     * Download registration documents
     */
    public function downloadDokumen(string $id, string $type): JsonResponse
    {
        $formulir = Formulir::findOrFail($id);

        $allowedTypes = ['pas_foto', 'scan_ktp', 'scan_ijazah', 'bukti_pembayaran'];
        
        if (!in_array($type, $allowedTypes)) {
            return response()->json([
                'message' => 'Invalid document type'
            ], 400);
        }

        if (!$formulir->$type) {
            return response()->json([
                'message' => 'Document not found'
            ], 404);
        }

        $path = Storage::disk('public')->path($formulir->$type);
        
        if (!file_exists($path)) {
            return response()->json([
                'message' => 'File not found'
            ], 404);
        }

        return response()->json([
            'data' => [
                'url' => Storage::disk('public')->url($formulir->$type)
            ]
        ]);
    }
}
