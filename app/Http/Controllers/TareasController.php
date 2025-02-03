<?php

namespace App\Http\Controllers;

use App\Models\TareaLogistica;
use App\Models\TareaRedes;
use Illuminate\Http\Request;
use App\Models\MaterialDisponible;

class TareasController extends Controller
{
    public function AsignarTarea(Request $request)
    {
        if ($request->CODLOGISTICA) {
            $logistica = new TareaLogistica();
            $logistica->CODTAREAL = $request->CODSOLICITUD;
            $logistica->RESPONSABLERECEPCION  = $request->RESPONSABLE;
            $logistica->DESTINO  = $request->DESTINO;
            $logistica->FECHATAREA  = $request->FECHATAREA;
            $logistica->CODSOLICITUD  = $request->CODSOLICITUD;
            $logistica->CODLOGISTICA = $request->CODLOGISTICA;
            $logistica->save();
            foreach ($request->MATERIALES as $materia) {
                $materialN = new MaterialDisponible();
                $materialN->CODTAREAL = $request->CODSOLICITUD;
                $materialN->MATERIAL = $materia['MATERIALPEDIDO'];
                $materialN->CANTIDAD = $materia['CANTIDADPEDIDA'];
                $materialN->save();
            }
            
        }
        if ($request->CODREDES) {
            $redes = new TareaRedes();
            $redes->CODTAREA = $request->CODSOLICITUD;
            $redes->RESPONSABLE  = $request->RESPONSABLE;
            $redes->UBICACION  = $request->DESTINO;
            $redes->FECHA  = $request->FECHATAREA;
            $redes->CODSOLICITUD  = $request->CODSOLICITUD;
            $redes->CODREDES = $request->CODREDES;
            $redes->save();
        }
        $solicitudController = new SolicitudController();
        $solicitudController->aceptarSolicitud($request->CODSOLICITUD ,$request->ADMIN,$request->FECHAAPROBADA);
        return "llega";
    }
}
