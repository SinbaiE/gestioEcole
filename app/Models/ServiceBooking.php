<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceBooking extends Model
{
    use HasFactory;

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new TenantScope);
    }

    protected $fillable = [
        'hotel_id',
        'service_id',
        'guest_id',
        'reservation_id',
        'booking_number',
        'service_date',
        'service_time',
        'quantity',
        'guests_count',
        'unit_price',
        'total_amount',
        'status',
        'special_requests',
        'notes',
    ];

    protected $casts = [
        'service_date' => 'datetime',
        'service_time' => 'datetime',
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'confirmed' => 'blue',
            'in_progress' => 'purple',
            'completed' => 'green',
            'cancelled' => 'red',
            default => 'gray'
        };
    }
}
