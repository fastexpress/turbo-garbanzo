<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    public function userCreated()
    {
        return $this->belongsTo('App\Models\User', 'userCreated', 'id');
    }
    public function userUpdated()
    {
        return $this->belongsTo('App\Models\User', 'userUpdated', 'id');
    }
    public function inCharge()
    {
        return $this->belongsTo('App\Models\User', 'inCharge', 'id');
    }
}
