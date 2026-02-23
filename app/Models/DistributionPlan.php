<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistributionPlan extends Model
{
    use HasFactory;
    protected $fillable = ['dealer_id', 'month', 'target_quantity'];

    public function dealer()
    {
        return $this->belongsTo(Dealer::class);
    }
}