<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departament extends Model
{
    use HasFactory;
    public function departament()
    {
        return $this->belongsTo(Departament::class, 'idDepartament', 'id');
    }
}
