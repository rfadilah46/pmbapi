<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PembukaanProdi extends Model
{
    use HasFactory;

    protected $table = 'pembukaan_prodi';

    protected $fillable = [
        'program_studi_id',
        'tahun_ajaran_id',
        'kuota'
    ];

    protected $casts = [
        'kuota' => 'integer'
    ];

    public function programStudi(): BelongsTo
    {
        return $this->belongsTo(ProgramStudi::class);
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function scopeAktif($query)
    {
        return $query->whereHas('tahunAjaran', function($q) {
            $q->where('aktif', true);
        });
    }

    public function getJumlahPendaftarAttribute()
    {
        return Formulir::where('program_studi_id', $this->program_studi_id)
                      ->where('tahun_ajaran_id', $this->tahun_ajaran_id)
                      ->count();
    }

    public function getSisaKuotaAttribute()
    {
        return $this->kuota - $this->jumlah_pendaftar;
    }
}
