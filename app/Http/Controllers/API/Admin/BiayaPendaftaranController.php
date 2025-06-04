<?php

namespace App\Http\Controllers\API\Admin;

use App\Models\BiayaPendaftaran;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BiayaPendaftaranController extends Controller
{
    /**
     * Display a listing of registration fees
     */
    public function index(): JsonResponse
    {
        $biaya = BiayaPendaftaran::orderBy('created_at', 'desc')->get();

        return response()->json([
            'data' => $biaya
        ]);
    }

    /**
     * Store a newly created registration fee
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nominal' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'aktif' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // If new fee is set as active, deactivate all other fees
        if ($request->aktif) {
            BiayaPendaftaran::where('aktif', true)->update(['aktif' => false]);
        }

        $biaya = BiayaPendaftaran::create($request->all());

        return response()->json([
            'message' => 'Registration fee created successfully',
            'data' => $biaya
        ], 201);
    }

    /**
     * Display the specified registration fee
     */
    public function show(string $id): JsonResponse
    {
        $biaya = BiayaPendaftaran::findOrFail($id);

        return response()->json([
            'data' => $biaya
        ]);
    }

    /**
     * Update the specified registration fee
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nominal' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'aktif' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $biaya = BiayaPendaftaran::findOrFail($id);

        // If updating to active, deactivate all other fees
        if ($request->aktif && !$biaya->aktif) {
            BiayaPendaftaran::where('aktif', true)->update(['aktif' => false]);
        }

        $biaya->update($request->all());

        return response()->json([
            'message' => 'Registration fee updated successfully',
            'data' => $biaya
        ]);
    }

    /**
     * Remove the specified registration fee
     */
    public function destroy(string $id): JsonResponse
    {
        $biaya = BiayaPendaftaran::findOrFail($id);
        $biaya->delete();

        return response()->json([
            'message' => 'Registration fee deleted successfully'
        ]);
    }

    /**
     * Get current active registration fee
     */
    public function current(): JsonResponse
    {
        $biaya = BiayaPendaftaran::aktif()->first();

        if (!$biaya) {
            return response()->json([
                'message' => 'No active registration fee found'
            ], 404);
        }

        return response()->json([
            'data' => $biaya
        ]);
    }
}
