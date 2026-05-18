<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttachmentFile extends Model
{
    use HasFactory;

    protected $table = 'attachment_files';

    protected $fillable = [
        'uuid',
        'type',
        'file_name',
        'file_name_original',
        'file_path',
        'file_size',
        'file_type',
        'client_session_id',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'type' => 'string',
    ];

    /**
     * Exemple : accès direct à l'URL du fichier
     */
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }
}