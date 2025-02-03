<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Redes extends Model
{
    use HasFactory;
    protected $table = "redes";
    protected $primaryKey = "CODREDES";
    protected $fillable = [
        "CODREDES",
        "CONTRASENAREDES",
        "NOMBREREDES",
        "ENCARGADOREDES",
        "CELULARREDES"
    ];
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
}
