<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\LogsActivity;

class AssetCategory extends Model
{
    use LogsActivity;
    protected $fillable = ['name', 'description'];

    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class, 'category_id');
    }
}
