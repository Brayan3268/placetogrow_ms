<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;

class Invoice extends Model
{
    use HasFactory;

    protected $primaryKey = 'reference';

    public $incrementing = false;

    protected $keyType = 'array';

    protected $fillable = [
        'reference',
        'amount',
        'currency',
        'status',
        'site_id',
        'user_id',
        'payment_id',
        'date_created',
        'date_surcharge',
        'amount_surcharge',
        'date_expiration',
    ];

    public function newUniqueId(): string
    {
        return (string) Uuid::uuid4();
    }

    public function uniqueIds(): array
    {
        return ['reference'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }
}
