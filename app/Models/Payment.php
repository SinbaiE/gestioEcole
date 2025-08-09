<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
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
        'invoice_id',
        'guest_id',
        'payment_number',
        'amount',
        'payment_method',
        'transaction_reference',
        'status',
        'payment_date',
        'notes',
        'payment_details',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'datetime',
        'payment_details' => 'array',
    ];

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match($this->payment_method) {
            'cash' => 'Espèces',
            'card' => 'Carte bancaire',
            'bank_transfer' => 'Virement bancaire',
            'orange_money' => 'Orange Money',
            'mtn_mobile_money' => 'MTN Mobile Money',
            default => 'Autre'
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'completed' => 'green',
            'failed' => 'red',
            'refunded' => 'gray',
            default => 'gray'
        };
    }
}
