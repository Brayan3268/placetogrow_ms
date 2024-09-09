<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;

class Usersuscription extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'array';

    protected $fillable = [
        'reference',
        'user_id',
        'expiration_time',
        'days_until_next_payment',
        'suscription_id',
        'status',
        'request_id',
        'additional_data',
    ];

    public function newUniqueId(): string
    {
        return (string) Uuid::uuid4();
    }

    public function uniqueIds(): array
    {
        return ['reference'];
    }

    public function suscription(): BelongsTo
    {
        return $this->belongsTo(Suscription::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
