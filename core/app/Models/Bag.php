<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bag extends Model
{
    use HasFactory;
    public function userTraveler()
    {
        return $this->belongsTo('App\Models\User', 'userTraveler', 'id');
    }
    public function packages()
    {
        return $this->hasMany('App\Models\Package', 'idBag');
    }
}
