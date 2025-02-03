<?php

namespace App\Http\Controllers;

use App\Models\Participantes;
use Illuminate\Http\Request;

class ParticipantesController extends Controller
{
    public function subirLista(Request $request)
    {
        $codSolicitud = $request->CODSOLICITUD;
        $miembros = array_merge($request->miembrosAñadidos, $request->miembrosAñadidosOtra);
        foreach ($miembros as $miembro) {
            $participante = new Participantes();
            $participante->CODSOLICITUD = $codSolicitud;
            $participante->NOMBREPARTICIPANTE = $miembro['NOMBREMIEMBRO'];
            $participante->PERTENECE = $miembro['PERTENECE'];
            $participante->save();
        }
        return 'Lista subida con éxito';
    }
    public function obtenerMiembrosAgregados($id)
    {
        $participantes = Participantes::where('CODSOLICITUD', $id)->get();

        // Agrupar por el campo PERTENECE
        $agrupados = $participantes->groupBy('PERTENECE');

        return $participantes;
    }
}
