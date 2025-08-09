<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoomType extends Model
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
        'name',
        'description',
        'base_price',
        'max_occupancy',
        'bed_count',
        'bed_type',
        'room_size',
        'amenities',
        'images',
        'is_active',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'room_size' => 'decimal:2',
        'amenities' => 'array',
        'images' => 'array',
        'is_active' => 'boolean',
    ];

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function getAvailableRoomsCount(): int
    {
        return $this->rooms()->where('status', 'available')->count();
    }
}
