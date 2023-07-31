<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentUSA extends Model
{
    use HasFactory;
    public function bags()
    {
        return $this->hasMany('App\Models\Bag', 'idShipment');
    }
    public function inCharge()
    {
        return $this->belongsTo('App\Models\User', 'inCharge', 'id');
    }
}
