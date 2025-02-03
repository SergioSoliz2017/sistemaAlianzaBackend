<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialDisponible extends Model
{
    use HasFactory;
    protected $table = "materialdisponible";
    protected $fillable = [
        "CODTAREAL",
        "MATERIAL",
        "CANTIDAD"
    ];
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
}
