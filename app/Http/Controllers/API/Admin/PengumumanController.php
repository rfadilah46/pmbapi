<?php

namespace App\Http\Controllers\API\Admin;

use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PengumumanController extends Controller
{
    /**
     * Display a listing of announcements
     */
    public function index(): JsonResponse
    {
        $pengumuman = Pengumuman::orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'data' => $pengumuman,
            'meta' => [
                'total' => $pengumuman->total(),
                'per_page' => $pengumuman->perPage(),
                'current_page' => $pengumuman->currentPage(),
                'last_page' => $pengumuman->lastPage(),
            ]
        ]);
    }

    /**
     * Store a newly created announcement
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'tanggal_publish' => 'nullable|date',
            'status' => 'required|in:draft,published'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $pengumuman = Pengumuman::create($request->all());

        return response()->json([
            'message' => 'Announcement created successfully',
            'data' => $pengumuman
        ], 201);
    }

    /**
     * Display the specified announcement
     */
    public function show(string $id): JsonResponse
    {
        $pengumuman = Pengumuman::findOrFail($id);

        return response()->json([
            'data' => $pengumuman
        ]);
    }

    /**
     * Update the specified announcement
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'tanggal_publish' => 'nullable|date',
            'status' => 'required|in:draft,published'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $pengumuman = Pengumuman::findOrFail($id);
        $pengumuman->update($request->all());

        return response()->json([
            'message' => 'Announcement updated successfully',
            'data' => $pengumuman
        ]);
    }

    /**
     * Remove the specified announcement
     */
    public function destroy(string $id): JsonResponse
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $pengumuman->delete();

        return response()->json([
            'message' => 'Announcement deleted successfully'
        ]);
    }

    /**
     * Display published announcements
     */
    public function published(): JsonResponse
    {
        $pengumuman = Pengumuman::published()
            ->orderBy('tanggal_publish', 'desc')
            ->paginate(10);

        return response()->json([
            'data' => $pengumuman,
            'meta' => [
                'total' => $pengumuman->total(),
                'per_page' => $pengumuman->perPage(),
                'current_page' => $pengumuman->currentPage(),
                'last_page' => $pengumuman->lastPage(),
            ]
        ]);
    }
}
