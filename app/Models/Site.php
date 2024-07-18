<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Site extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'document',
        'document_type',
        'category_id',
        'expiration_time',
        'current_type',
        'site_type',
        'image',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function fieldspaysites(): HasMany
    {
        return $this->hasMany(Fieldspaysite::class);
    }
}
