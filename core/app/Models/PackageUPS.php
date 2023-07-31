<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageUPS extends Model
{
    use HasFactory;

    protected $primaryKey = 'id'; // or null
    public $incrementing = false;
    protected $keyType = 'string';

    public function boxes()
    {
        return $this->hasMany('App\Models\BoxUPS', 'idPackage', 'id');
    }
    public function baler()
    {
        return $this->hasMany('App\Models\Baler', 'id', 'idBaler');
    }
    public function userCreateds()
    {
        return $this->hasMany('App\Models\User', 'id', 'userCreated');
    }
    public function userUpdateds()
    {
        return $this->hasMany('App\Models\User', 'id', 'userUpdated');
    }
    public function contents()
    {
        return $this->hasMany('App\Models\ContentUPS', 'idPackage', 'id');
    }
    public function customer()
    {
        return $this->hasManyThrough(
            Customer::class,
            PackageUPSCustomer::class,
            'idPackageUPS',
            'id',
            'id',
            'idCustomer'
        )->select(['idPackageUPS', 'idCustomer', 'idAccountPersonal', 'balance', 'weight', 'cost', 'typePaymentTypical', 'subtotal', 'customers.name']);
    }
    public function account()
    {
        return $this->hasManyThrough(
            Account::class,
            PackageUPSCustomer::class,
            'idPackageUPS',
            'id',
            'id',
            'idAccountPersonal'
        );
    }
}
