<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TareaRedes extends Model
{
    use HasFactory;
    protected $table = "tarearedes";
    protected $primaryKey = "CODTAREAR";
    protected $fillable = [
        "CODTAREA ",
        "RESPONSABLE",
        "FECHA",
        "UBICACION",
        "CODSOLICITUD" ,
        "CODREDES" 
    ];
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    public function redes()
{
    return $this->belongsTo(Redes::class, 'CODREDES', 'CODREDES');
}
}
