<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Usersuscription extends Model
{
    use HasFactory;

    protected $primaryKey = ['plan_name', 'site_id'];

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'last_name',
        'document_type',
        'document',
        'email',
        'suscription_id',
        'user_id',
    ];

    public function suscription(): BelongsTo
    {
        return $this->belongsTo(Suscription::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
