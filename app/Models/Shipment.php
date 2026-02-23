<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;
    protected $fillable = [
        'unit_id',
        'dealer_id',
        'origin_id',
        'status',
        'dispatched_at',
        'arrived_at'
    ];

    public function origin()
    {
        return $this->belongsTo(Warehouse::class , 'origin_id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'dispatched_at' => 'datetime',
            'arrived_at' => 'datetime',
        ];
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function dealer()
    {
        return $this->belongsTo(Dealer::class);
    }

    /**
     * Get the lead time in days.
     */
    public function getLeadTimeInDaysAttribute(): ?int
    {
        if ($this->dispatched_at && $this->arrived_at) {
            return $this->dispatched_at->diffInDays($this->arrived_at);
        }

        return null;
    }

    /**
     * Get current transit days for ongoing shipments.
     */
    public function getTransitDaysAttribute(): int
    {
        if ($this->dispatched_at && !$this->arrived_at) {
            return $this->dispatched_at->diffInDays(now());
        }

        return 0;
    }

    protected static function booted()
    {
        // When shipment status is updated, we update the Unit's status and timestamps
        static::saving(function ($shipment) {
            if ($shipment->isDirty('status')) {
                // Set dispatched_at when moving from pending to dispatched/on_progress
                if (in_array($shipment->status, ['dispatched', 'on_progress']) && !$shipment->dispatched_at) {
                    $shipment->dispatched_at = now();
                }

                // Set arrived_at when moving to delivered
                if ($shipment->status === 'delivered' && !$shipment->arrived_at) {
                    $shipment->arrived_at = now();
                }
            }
        });

        static::saved(function ($shipment) {
            if ($shipment->isDirty('status')) {
                $unit = $shipment->unit;

                if ($unit) {
                    if ($shipment->status === 'pending') {
                        $unit->update(['status' => 'booked']);
                    }
                    elseif (in_array($shipment->status, ['dispatched', 'on_progress', 'delayed'])) {
                        $unit->update(['status' => 'in_transit']);
                    }
                    elseif ($shipment->status === 'delivered') {
                        $unit->update(['status' => 'delivered']);
                    }
                }
            }
        });
    }
}