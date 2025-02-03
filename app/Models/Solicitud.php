<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    use HasFactory;

    protected $table = "solicitud";
    protected $primaryKey = "CODSOLICITUD";
    protected $fillable = [
        "CODSOLICITUD",
        "DESCRIPCION",
        "FECHASOLICITADA",
        "FECHAPROGRAMADA",
        "TIPOEVENTO","HORAEVENTO","FECHAAPROBADA"
    ];
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    public function CasaCampañas()
    {
        return $this->belongsToMany(CasaCampaña::class, 'solicitudcasa', 'CODSOLICITUD', 'CODCASACAMPANA');
    }
    public function materiales()
    {
        return $this->hasMany(MaterialPedido::class, 'CODSOLICITUD');
    }
    public function tareaLogistica()
{
    return $this->hasOne(TareaLogistica::class, 'CODSOLICITUD', 'CODSOLICITUD')
                ->with('logistica');
}

public function tareaRedes()
{
    return $this->hasOne(TareaRedes::class, 'CODSOLICITUD', 'CODSOLICITUD')
                ->with('redes');
}
}
