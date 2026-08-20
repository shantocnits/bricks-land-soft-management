<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\LogsActivity;

class DocumentFile extends Model
{
    use LogsActivity;
    protected $fillable = [
        'folder_id',
        'title',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'description',
    ];

    public function folder(): BelongsTo
    {
        return $this->belongsTo(DocumentFolder::class, 'folder_id');
    }
}
