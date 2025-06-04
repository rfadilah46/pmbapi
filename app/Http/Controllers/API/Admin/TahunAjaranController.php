<?php

namespace App\Http\Controllers\API\Admin;

use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TahunAjaranController extends Controller
{
    /**
     * Display a listing of academic years
     */
    public function index(): JsonResponse
    {
        $tahunAjaran = TahunAjaran::withCount('pembukaanProdi')
            ->orderBy('tahun', 'desc')
            ->orderBy('semester')
            ->get();

        return response()->json([
            'data' => $tahunAjaran
        ]);
    }

    /**
     * Store a newly created academic year
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required|string|size:4',
            'semester' => 'required|in:Ganjil,Genap',
            'aktif' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Check for duplicate tahun ajaran
        $exists = TahunAjaran::where('tahun', $request->tahun)
            ->where('semester', $request->semester)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Academic year already exists'
            ], 422);
        }

        // If new tahun ajaran is set as active, deactivate all others
        if ($request->aktif) {
            TahunAjaran::where('aktif', true)->update(['aktif' => false]);
        }

        $tahunAjaran = TahunAjaran::create($request->all());

        return response()->json([
            'message' => 'Academic year created successfully',
            'data' => $tahunAjaran
        ], 201);
    }

    /**
     * Display the specified academic year
     */
    public function show(string $id): JsonResponse
    {
        $tahunAjaran = TahunAjaran::with(['pembukaanProdi' => function($query) {
                $query->with('programStudi');
            }])
            ->findOrFail($id);

        return response()->json([
            'data' => $tahunAjaran
        ]);
    }

    /**
     * Update the specified academic year
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required|string|size:4',
            'semester' => 'required|in:Ganjil,Genap',
            'aktif' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $tahunAjaran = TahunAjaran::findOrFail($id);

        // Check for duplicate tahun ajaran
        $exists = TahunAjaran::where('tahun', $request->tahun)
            ->where('semester', $request->semester)
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Academic year already exists'
            ], 422);
        }

        // If updating to active, deactivate all others
        if ($request->aktif && !$tahunAjaran->aktif) {
            TahunAjaran::where('aktif', true)->update(['aktif' => false]);
        }

        $tahunAjaran->update($request->all());

        return response()->json([
            'message' => 'Academic year updated successfully',
            'data' => $tahunAjaran
        ]);
    }

    /**
     * Remove the specified academic year
     */
    public function destroy(string $id): JsonResponse
    {
        $tahunAjaran = TahunAjaran::findOrFail($id);

        // Check if there are any related formulir
        if ($tahunAjaran->formulir()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete academic year with existing registrations'
            ], 422);
        }

        $tahunAjaran->delete();

        return response()->json([
            'message' => 'Academic year deleted successfully'
        ]);
    }

    /**
     * Set academic year as active
     */
    public function setAktif(string $id): JsonResponse
    {
        $tahunAjaran = TahunAjaran::findOrFail($id);

        // Deactivate all other academic years
        TahunAjaran::where('aktif', true)->update(['aktif' => false]);

        // Set the selected academic year as active
        $tahunAjaran->update(['aktif' => true]);

        return response()->json([
            'message' => 'Academic year set as active successfully',
            'data' => $tahunAjaran
        ]);
    }
}
