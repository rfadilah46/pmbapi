<?php

namespace App\Http\Controllers\API\Admin;

use App\Models\PembukaanProdi;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PembukaanProdiController extends Controller
{
    /**
     * Display a listing of program study openings
     */
    public function index(Request $request): JsonResponse
    {
        $query = PembukaanProdi::with(['programStudi', 'tahunAjaran']);

        // Filter by tahun ajaran
        if ($request->has('tahun_ajaran_id')) {
            $query->where('tahun_ajaran_id', $request->tahun_ajaran_id);
        }

        // Filter by program studi
        if ($request->has('program_studi_id')) {
            $query->where('program_studi_id', $request->program_studi_id);
        }

        // Get only active ones
        if ($request->boolean('aktif')) {
            $query->whereHas('tahunAjaran', function($q) {
                $q->where('aktif', true);
            });
        }

        $pembukaanProdi = $query->orderBy('created_at', 'desc')->get();

        // Add pendaftar count and sisa kuota
        $pembukaanProdi->each(function($item) {
            $item->jumlah_pendaftar = $item->jumlah_pendaftar;
            $item->sisa_kuota = $item->sisa_kuota;
        });

        return response()->json([
            'data' => $pembukaanProdi
        ]);
    }

    /**
     * Store a newly created program study opening
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'program_studi_id' => 'required|exists:program_studi,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
            'kuota' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Check if combination already exists
        $exists = PembukaanProdi::where('program_studi_id', $request->program_studi_id)
            ->where('tahun_ajaran_id', $request->tahun_ajaran_id)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Program study opening already exists for this academic year'
            ], 422);
        }

        $pembukaanProdi = PembukaanProdi::create($request->all());

        return response()->json([
            'message' => 'Program study opening created successfully',
            'data' => $pembukaanProdi->load(['programStudi', 'tahunAjaran'])
        ], 201);
    }

    /**
     * Display the specified program study opening
     */
    public function show(string $id): JsonResponse
    {
        $pembukaanProdi = PembukaanProdi::with(['programStudi', 'tahunAjaran'])
            ->findOrFail($id);

        // Add pendaftar count and sisa kuota
        $pembukaanProdi->jumlah_pendaftar = $pembukaanProdi->jumlah_pendaftar;
        $pembukaanProdi->sisa_kuota = $pembukaanProdi->sisa_kuota;

        return response()->json([
            'data' => $pembukaanProdi
        ]);
    }

    /**
     * Update the specified program study opening
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'program_studi_id' => 'required|exists:program_studi,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
            'kuota' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $pembukaanProdi = PembukaanProdi::findOrFail($id);

        // Check if combination already exists (excluding current record)
        $exists = PembukaanProdi::where('program_studi_id', $request->program_studi_id)
            ->where('tahun_ajaran_id', $request->tahun_ajaran_id)
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Program study opening already exists for this academic year'
            ], 422);
        }

        // Check if new kuota is less than current pendaftar
        if ($request->kuota < $pembukaanProdi->jumlah_pendaftar) {
            return response()->json([
                'message' => 'New quota cannot be less than current registrants'
            ], 422);
        }

        $pembukaanProdi->update($request->all());

        return response()->json([
            'message' => 'Program study opening updated successfully',
            'data' => $pembukaanProdi->load(['programStudi', 'tahunAjaran'])
        ]);
    }

    /**
     * Remove the specified program study opening
     */
    public function destroy(string $id): JsonResponse
    {
        $pembukaanProdi = PembukaanProdi::findOrFail($id);

        // Check if there are any registrants
        if ($pembukaanProdi->jumlah_pendaftar > 0) {
            return response()->json([
                'message' => 'Cannot delete program study opening with existing registrants'
            ], 422);
        }

        $pembukaanProdi->delete();

        return response()->json([
            'message' => 'Program study opening deleted successfully'
        ]);
    }
}
