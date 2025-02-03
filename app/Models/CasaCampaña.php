<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CasaCampaña extends Model
{
    use HasFactory;

    protected $table = "casacampana";
    protected $primaryKey = "CODCASACAMPANA";
    protected $fillable = [
        "CODCASACAMPANA",
        "NOMBREENCARGADO",
        "DIRECCION",
        "UBICACION",
        "CONTRASENACASA",
        "CELULARCASA",
        "CORREOCASA"
    ];
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    public function Solicitudes()
    {
        return $this->belongsToMany(Solicitud::class, 'solicitudcasa', 'CODCASACAMPANA', 'CODSOLICITUD');
    }
}
