<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectGT extends Model
{
    use HasFactory;
    public function town()
    {
        return $this->belongsTo(Town::class, 'idTown', 'id');
    }
    // public function shipment()
    // {
    //     return $this->belongsTo(ShipmentUSA::class, 'idShipmentUSA', 'id');
    // }
    public function departament()
    {
        return $this->hasOneThrough(Departament::class, Town::class, 'id', 'id', 'idTown', 'idDepartament');
    }
    public function userCollect()
    {
        return $this->belongsTo('App\Models\User', 'userCollect', 'id')->withDefault([
            'id' => 0,
            'name' => 'Pendiente',
        ]);
    }
    public function userCreated()
    {
        return $this->belongsTo('App\Models\User', 'userCreated', 'id');
    }
    public function userUpdated()
    {
        return $this->belongsTo('App\Models\User', 'userUpdated', 'id');
    }
}
