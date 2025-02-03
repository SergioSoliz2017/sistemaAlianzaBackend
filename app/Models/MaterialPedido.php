<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialPedido extends Model
{
    use HasFactory;
    protected $table = "materialpedido";
    protected $fillable = [
        "CODSOLICITUD",
        "MATERIALPEDIDO",
        "CANTIDADPEDIDA"
    ];
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
}
