<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fieldspaysite extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_user_see',
        'type',
        'is_modified',
        'is_user_see',
    ];

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }
}
