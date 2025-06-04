<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pengumuman extends Model
{
    use HasFactory;

    protected $table = 'pengumuman';

    protected $fillable = [
        'judul',
        'konten',
        'tanggal_publish',
        'status'
    ];

    protected $casts = [
        'tanggal_publish' => 'datetime'
    ];

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where('tanggal_publish', '<=', now());
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function isPublished(): bool
    {
        return $this->status === 'published' && 
               $this->tanggal_publish && 
               $this->tanggal_publish->isPast();
    }
}
