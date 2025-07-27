<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guest extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'nationality',
        'id_type',
        'id_number',
        'address',
        'city',
        'country',
        'guest_type',
        'preferences',
        'loyalty_points',
        'last_stay',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'preferences' => 'array',
        'last_stay' => 'datetime',
    ];

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getTotalStaysAttribute(): int
    {
        return $this->reservations()->where('status', 'checked_out')->count();
    }
}
