<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MiembrosCasaCampaña extends Model
{
    use HasFactory;
    protected $table = "miembroscasacampana";
    protected $fillable = [
        "CODCASACAMPANA",
        "NOMBREMIEMBRO",
        "CELULARMIEMBRO",
        "CORREOMIEMBRO",
        "CARGO"
    ];
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
}
