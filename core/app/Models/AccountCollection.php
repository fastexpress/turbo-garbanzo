<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountCollection extends Model
{
    use HasFactory;
    public function ups()
    {
        return $this->belongsTo('App\Models\PackageUPS', 'idPackageUPS', 'id');
    }
    public function account()
    {
        return $this->belongsTo('App\Models\Account', 'idAccount', 'id');
    }
}
