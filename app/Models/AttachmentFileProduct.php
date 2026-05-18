<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttachmentFileProduct extends Model
{
    use HasFactory;

    protected $table = 'attachment_file_products';

    protected $fillable = [
        'attachment_file_id',
        'product_id',
        'position',
    ];

    protected $casts = [
        'attachment_file_id' => 'integer',
        'product_id' => 'integer',
        'position' => 'integer',
    ];

    /**
     * Relation vers le fichier attaché
     */
    public function attachmentFile()
    {
        return $this->belongsTo(AttachmentFile::class);
    }

    /**
     * Relation vers le produit
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}