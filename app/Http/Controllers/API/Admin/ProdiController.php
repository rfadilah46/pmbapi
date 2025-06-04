<?php

namespace App\Http\Controllers\API\Admin;

use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProdiController extends Controller
{
    /**
     * Display a listing of program studies
     */
    public function index(): JsonResponse
    {
        $prodi = ProgramStudi::withCount(['pembukaanProdi', 'formulir'])
            ->orderBy('nama')
            ->get();

        return response()->json([
            'data' => $prodi
        ]);
    }

    /**
     * Store a newly created program study
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255|unique:program_studi,nama',
            'deskripsi' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $prodi = ProgramStudi::create($request->all());

        return response()->json([
            'message' => 'Program study created successfully',
            'data' => $prodi
        ], 201);
    }

    /**
     * Display the specified program study
     */
    public function show(string $id): JsonResponse
    {
        $prodi = ProgramStudi::with(['pembukaanProdi' => function($query) {
                $query->with('tahunAjaran')->orderBy('created_at', 'desc');
            }])
            ->withCount('formulir')
            ->findOrFail($id);

        return response()->json([
            'data' => $prodi
        ]);
    }

    /**
     * Update the specified program study
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255|unique:program_studi,nama,' . $id,
            'deskripsi' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $prodi = ProgramStudi::findOrFail($id);
        $prodi->update($request->all());

        return response()->json([
            'message' => 'Program study updated successfully',
            'data' => $prodi
        ]);
    }

    /**
     * Remove the specified program study
     */
    public function destroy(string $id): JsonResponse
    {
        $prodi = ProgramStudi::findOrFail($id);

        // Check if there are any related formulir
        if ($prodi->formulir()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete program study with existing registrations'
            ], 422);
        }

        $prodi->delete();

        return response()->json([
            'message' => 'Program study deleted successfully'
        ]);
    }

    /**
     * Display available program studies for current academic year
     */
    public function available(): JsonResponse
    {
        $availableProdi = ProgramStudi::whereHas('pembukaanProdi', function($query) {
                $query->whereHas('tahunAjaran', function($q) {
                    $q->where('aktif', true);
                });
            })
            ->with(['pembukaanProdi' => function($query) {
                $query->whereHas('tahunAjaran', function($q) {
                    $q->where('aktif', true);
                })->with('tahunAjaran');
            }])
            ->get();

        return response()->json([
            'data' => $availableProdi
        ]);
    }
}
