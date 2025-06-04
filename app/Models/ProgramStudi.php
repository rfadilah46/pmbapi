<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProgramStudi extends Model
{
    use HasFactory;

    protected $table = 'program_studi';

    protected $fillable = [
        'nama',
        'deskripsi'
    ];

    public function pembukaanProdi(): HasMany
    {
        return $this->hasMany(PembukaanProdi::class);
    }

    public function formulir(): HasMany
    {
        return $this->hasMany(Formulir::class);
    }
}
