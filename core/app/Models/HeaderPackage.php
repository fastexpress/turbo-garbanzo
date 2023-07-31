<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeaderPackage extends Model
{
    use HasFactory;

    protected $primaryKey = 'id'; // or null
    public $incrementing = false;
    protected $keyType = 'string';

    public function packages()
    {
        return $this->hasMany('App\Models\Package', 'idHeaderPackage');
    }
}
