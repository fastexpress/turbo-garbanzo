<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageUPSCustomer extends Model
{
    use HasFactory;
    public function customers()
    {
        return $this->hasMany('App\Models\Customer', 'id', 'idCustomer');
    }
    public function accounts()
    {
        return $this->hasMany('App\Models\Account', 'id', 'idAccountPersonal');
    }
}
