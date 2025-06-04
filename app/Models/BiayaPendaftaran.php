<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BiayaPendaftaran extends Model
{
    use HasFactory;

    protected $table = 'biaya_pendaftaran';

    protected $fillable = [
        'nominal',
        'deskripsi',
        'aktif'
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'aktif' => 'boolean'
    ];

    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }
}
