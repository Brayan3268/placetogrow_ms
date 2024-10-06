<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Suscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'amount',
        'currency_type',
        'expiration_time',
        'frecuency_collection',
        'site_id',
        'number_trys',
        'how_often_days',
        'date_try',
    ];

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function usersuscription()
    {
        return $this->hasMany(Usersuscription::class);
    }
}
