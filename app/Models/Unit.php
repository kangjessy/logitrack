<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;
    protected $fillable = ['model_name', 'vin_number', 'engine_number', 'color', 'production_year', 'status', 'warehouse_id'];

    public static function getGloballySearchableAttributes(): array
    {
        return ['vin_number', 'model_name'];
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}