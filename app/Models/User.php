<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable //implements MustVerifyEmail
{
    use HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'name',
        'last_name',
        'email',
        'password',
        'document',
        'document_type',
        'phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function payment()
    {
        return $this->hasMany(Payment::class);
    }

    public function invoice()
    {
        return $this->hasMany(Invoice::class);
    }

    public function usersuscription()
    {
        return $this->hasMany(UserSuscription::class);
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
