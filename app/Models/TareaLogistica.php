<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TareaLogistica extends Model
{
    use HasFactory;
    protected $table = "tarealogistica";
    protected $primaryKey = "CODTAREAL";
    protected $fillable = [
        "CODTAREAL",
        "RESPONSABLERECEPCION",
        "DESTINO",
        "FECHATAREA",
        "CODLOGISTICA" ,
        "CODSOLICITUD" 
    ];
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    public function logistica()
{
    return $this->belongsTo(Logistica::class, 'CODLOGISTICA', 'CODLOGISTICA');
}
}
