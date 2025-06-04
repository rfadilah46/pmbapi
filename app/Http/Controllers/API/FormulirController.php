<?php

namespace App\Http\Controllers\API;

use App\Models\Formulir;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FormulirController extends Controller
{
    /**
     * Store a new registration form
     */
    public function store(Request $request): JsonResponse
    {
        // Check if user already has a formulir
        if (auth()->user()->formulir) {
            return response()->json([
                'message' => 'You have already submitted a registration form'
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            // Data Pribadi
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|size:16|unique:formulir',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:15',
            'email' => 'required|email|max:255',

            // Data Orang Tua
            'nama_ayah' => 'required|string|max:255',
            'nama_ibu' => 'required|string|max:255',
            'pekerjaan_ayah' => 'required|string|max:255',
            'pekerjaan_ibu' => 'required|string|max:255',
            'nik_ayah' => 'required|string|size:16',
            'nik_ibu' => 'required|string|size:16',
            'no_hp_ortu' => 'required|string|max:15',

            // Data Pendidikan
            'asal_sekolah' => 'required|string|max:255',
            'tahun_lulus' => 'required|digits:4|integer|min:2000',
            'nilai_ijazah' => 'required|numeric|min:0|max:100',

            // Program Studi
            'program_studi_id' => 'required|exists:program_studi,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',

            // File Uploads
            'pas_foto' => 'required|image|max:2048', // max 2MB
            'scan_ktp' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'scan_ijazah' => 'required|file|mimes:pdf|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Handle file uploads
        $pasPhoto = $request->file('pas_foto')->store('formulir/pas-foto', 'public');
        $scanKtp = $request->file('scan_ktp')->store('formulir/scan-ktp', 'public');
        $scanIjazah = $request->file('scan_ijazah')->store('formulir/scan-ijazah', 'public');

        // Create formulir
        $formulir = Formulir::create([
            'user_id' => auth()->id(),
            'nama' => $request->nama,
            'nik' => $request->nik,
            'tanggal_lahir' => $request->tanggal_lahir,
            'tempat_lahir' => $request->tempat_lahir,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'nama_ayah' => $request->nama_ayah,
            'nama_ibu' => $request->nama_ibu,
            'pekerjaan_ayah' => $request->pekerjaan_ayah,
            'pekerjaan_ibu' => $request->pekerjaan_ibu,
            'nik_ayah' => $request->nik_ayah,
            'nik_ibu' => $request->nik_ibu,
            'no_hp_ortu' => $request->no_hp_ortu,
            'asal_sekolah' => $request->asal_sekolah,
            'tahun_lulus' => $request->tahun_lulus,
            'nilai_ijazah' => $request->nilai_ijazah,
            'program_studi_id' => $request->program_studi_id,
            'tahun_ajaran_id' => $request->tahun_ajaran_id,
            'pas_foto' => $pasPhoto,
            'scan_ktp' => $scanKtp,
            'scan_ijazah' => $scanIjazah,
        ]);

        return response()->json([
            'message' => 'Registration form submitted successfully',
            'data' => $formulir
        ], 201);
    }

    /**
     * Display user's registration form
     */
    public function show(): JsonResponse
    {
        $formulir = auth()->user()->formulir;

        if (!$formulir) {
            return response()->json([
                'message' => 'Registration form not found'
            ], 404);
        }

        return response()->json([
            'data' => $formulir->load(['programStudi', 'tahunAjaran'])
        ]);
    }

    /**
     * Upload payment proof
     */
    public function uploadBuktiBayar(Request $request): JsonResponse
    {
        $formulir = auth()->user()->formulir;

        if (!$formulir) {
            return response()->json([
                'message' => 'Registration form not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'bukti_pembayaran' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Delete old file if exists
        if ($formulir->bukti_pembayaran) {
            Storage::disk('public')->delete($formulir->bukti_pembayaran);
        }

        // Store new file
        $buktiPembayaran = $request->file('bukti_pembayaran')
            ->store('formulir/bukti-pembayaran', 'public');

        // Update formulir
        $formulir->update([
            'bukti_pembayaran' => $buktiPembayaran
        ]);

        return response()->json([
            'message' => 'Payment proof uploaded successfully',
            'data' => $formulir
        ]);
    }
}
