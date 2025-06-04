<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Formulir extends Model
{
    use HasUuids;

    protected $table = 'formulir';

    protected $fillable = [
        'user_id',
        'nama',
        'nik',
        'tanggal_lahir',
        'tempat_lahir',
        'alamat',
        'no_hp',
        'email',
        'nama_ayah',
        'nama_ibu',
        'pekerjaan_ayah',
        'pekerjaan_ibu',
        'nik_ayah',
        'nik_ibu',
        'no_hp_ortu',
        'asal_sekolah',
        'tahun_lulus',
        'nilai_ijazah',
        'program_studi_id',
        'tahun_ajaran_id',
        'pas_foto',
        'scan_ktp',
        'scan_ijazah',
        'bukti_pembayaran',
        'status_verifikasi',
        'catatan_verifikasi'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tahun_lulus' => 'integer',
        'nilai_ijazah' => 'decimal:2'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function programStudi(): BelongsTo
    {
        return $this->belongsTo(ProgramStudi::class);
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class);
    }
}
