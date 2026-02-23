<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dealer extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'region', 'address'];

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'region'];
    }

    public function distributionPlans()
    {
        return $this->hasMany(DistributionPlan::class);
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }
}