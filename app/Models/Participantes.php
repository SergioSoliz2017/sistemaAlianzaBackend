<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participantes extends Model
{
    use HasFactory;
    protected $table = 'participantes';

    protected $fillable = [
        'CODSOLICITUD',
        'NOMBREPARTICIPANTE',
        'PERTENECE',
    ];
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

}
