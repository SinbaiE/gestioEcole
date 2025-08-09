<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Service extends Model
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
        'category',
        'price',
        'pricing_type',
        'is_active',
        'availability_schedule',
        'max_capacity',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'availability_schedule' => 'array',
    ];

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function serviceBookings(): HasMany
    {
        return $this->hasMany(ServiceBooking::class);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function getCategoryLabelAttribute(): string
    {
        return match($this->category) {
            'spa' => 'Spa & Bien-être',
            'restaurant' => 'Restaurant',
            'bar' => 'Bar',
            'laundry' => 'Blanchisserie',
            'transport' => 'Transport',
            'business_center' => 'Centre d\'affaires',
            'fitness' => 'Fitness',
            'room_service' => 'Room Service',
            default => 'Autre'
        };
    }
}
