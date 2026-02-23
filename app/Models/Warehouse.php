<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'location'];

    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class , 'origin_id');
    }
}