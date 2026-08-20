<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\LogsActivity;

class DocumentFolder extends Model
{
    use LogsActivity;
    protected $fillable = [
        'name',
        'parent_id',
        'color',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(DocumentFolder::class, 'parent_id');
    }

    public function subfolders(): HasMany
    {
        return $this->hasMany(DocumentFolder::class, 'parent_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(DocumentFile::class, 'folder_id');
    }
}
