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
        'category_id',
        'expiration_time',
        'current_type',
        'site_type',
        'return_url',
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

    public function payment()
    {
        return $this->hasMany(Payment::class);
    }
}
