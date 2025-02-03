<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Directiva extends Model
{
    use HasFactory;
    protected $table = 'directiva';

    protected $fillable = [
        'CODCASACAMPANA',
        'NOMBRERESPONSABLELOGITICA',
        'NOMBRERESPONSABLEHACIENDA',
        'NOMBRERESPONSABLEACTAS',
        'NOMBRERESPONSABLEREDES',
        'NOMBRERESPONSABLEJUVENTUD'
    ];
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
}
