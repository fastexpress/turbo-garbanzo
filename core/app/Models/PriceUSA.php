<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceUSA extends Model
{
    use HasFactory;
    public function categories()
    {
        return $this->hasManyThrough(
            Category::class,
            CategoriesPriceUSA::class,
            'idPriceUSA',
            'id',
            'id',
            'idCategory'
        );
    }
    public function departaments()
    {
        return $this->hasManyThrough(
            Departament::class,
            DepartamentPriceUSA::class,
            'idPriceUSA',
            'id',
            'id',
            'idDepartament'
        );
    }
}
