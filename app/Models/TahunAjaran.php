<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TahunAjaran extends Model
{
    use HasFactory;

    protected $table = 'tahun_ajaran';

    protected $fillable = [
        'tahun',
        'semester',
        'aktif'
    ];

    protected $casts = [
        'aktif' => 'boolean'
    ];

    public function pembukaanProdi(): HasMany
    {
        return $this->hasMany(PembukaanProdi::class);
    }

    public function formulir(): HasMany
    {
        return $this->hasMany(Formulir::class);
    }

    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }
}
