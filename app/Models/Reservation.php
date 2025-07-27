<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_number',
        'hotel_id',
        'guest_id',
        'room_type_id',
        'room_id',
        'check_in_date',
        'check_out_date',
        'adults',
        'children',
        'nights',
        'room_rate',
        'total_amount',
        'paid_amount',
        'status',
        'payment_status',
        'source',
        'special_requests',
        'notes',
        'checked_in_at',
        'checked_out_at',
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'room_rate' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'checked_in_at' => 'datetime',
        'checked_out_at' => 'datetime',
    ];

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'confirmed' => 'blue',
            'checked_in' => 'green',
            'checked_out' => 'gray',
            'cancelled' => 'red',
            'no_show' => 'red',
            default => 'gray'
        };
    }

    public function getRemainingBalanceAttribute(): float
    {
        return $this->total_amount - $this->paid_amount;
    }
}
