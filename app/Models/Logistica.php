<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logistica extends Model
{
    use HasFactory;

    protected $table = "logistica";
    protected $primaryKey = "CODLOGISTICA";
    protected $fillable = [
        "CODLOGISTICA",
        "CONTRASENALOGISTICA",
        "NOMBRERESPONSABLE",
        "ENCARGADOLOGISTICA",
        "CELULARLOGISTICA"
    ];
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    
}
