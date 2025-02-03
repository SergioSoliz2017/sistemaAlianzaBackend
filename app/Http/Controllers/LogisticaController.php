<?php

namespace App\Http\Controllers;

use App\Models\Logistica;
use App\Models\Solicitud;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class LogisticaController extends Controller
{
    public function obtener()
    {
        return Logistica::all();
    }
    public function obtenerLogistica($id)
    {
        $logistica = Logistica::find($id);
        if (!$logistica) {
            return response()->json(['error' => 'Casa no encontrada'], 404);
        }

        try {
            $contraseñaCifrada = $logistica->CONTRASENALOGISTICA;
            $contraseña = Crypt::decryptString($contraseñaCifrada);
            $logistica->CONTRASENALOGISTICA = $contraseña;
        } catch (DecryptException $e) {
            return response()->json(['error' => 'Error al descifrar la contraseña'], 500);
        }
        return $logistica;
    }
    public function AgregarLogistica(Request $request)
    {
        $logistica = new Logistica();
        $logistica->CODLOGISTICA = $request->CODLOGISTICA;
        $logistica->CELULARLOGISTICA = $request->CELULARLOGISTICA;
        $logistica->NOMBRERESPONSABLE = $request->NOMBRERESPONSABLE;
        $logistica->ENCARGADOLOGISTICA = $request->ENCARGADOLOGISTICA;
        $logistica->CONTRASENALOGISTICA = Crypt::encryptString($request->CONTRASENALOGISTICA);
        $logistica->save();
        return "correcto";
    }
    public function obtenerSolicitudes($id)
    {
        $solicitudes = Solicitud::join('tarealogistica', 'solicitud.CODSOLICITUD', '=', 'tarealogistica.CODSOLICITUD')
            ->leftJoin('administrador', 'solicitud.ACEPTO', '=', 'administrador.CODADMINISTRADOR') // Usamos leftJoin
            ->where('tarealogistica.CODLOGISTICA', $id)
            ->with(['CasaCampañas', 'materiales'])->orderBy('FECHAPROGRAMADA', 'asc') // Cargar relaciones CasaCampañas y materiales
            ->select(
                'solicitud.*', // Todos los campos de la tabla Solicitud
                'administrador.NOMBREADMINISTRADOR', // Campo específico de Administrador
                'administrador.CELULARADMINISTRADOR' // Campo específico de Administrador
            )
            ->get();
        $nombre = Logistica::find($id);
        $solicitudesConImagenes = $solicitudes->map(function ($solicitud, $index) use ($nombre) {
            // Preparar los datos para enviar a getImages
            $request = new \Illuminate\Http\Request();
            $request->replace([
                'esAdmin' => false, // Si quieres usar lógica de administrador
                'codSolicitud' => $solicitud['CODSOLICITUD'],
                'codUsuario' => $nombre->NOMBRERESPONSABLE,
            ]);

            // Llamar al método getImages del controlador ImagenesController
            $imagenesController = app(\App\Http\Controllers\ImagenesController::class);
            $response = $imagenesController->getImages($request);

            // Verificar que la respuesta contenga imágenes
            $imagenes = $response->getData()->images ?? [];

            // Agregar las imágenes a la solicitud
            $solicitud['imagenes'] = $imagenes;

            return $solicitud;
        });

        return $solicitudesConImagenes;
    }




    public function modificarLogistica(Request $request, $id)
    {
        $logistica = Logistica::find($id);
        if (!$logistica) {
            return response()->json(['error' => 'Logistica no encontrada'], 404);
        }
        $logistica->NOMBRERESPONSABLE = $request->NOMBRERESPONSABLE;
        $logistica->ENCARGADOLOGISTICA = $request->ENCARGADOLOGISTICA;
        $logistica->CELULARLOGISTICA = $request->CELULARLOGISTICA;
        $logistica->CONTRASENALOGISTICA = Crypt::encryptString($request->CONTRASENALOGISTICA);

        $logistica->save();
        return 'Logistica modificada exitosamente';
    }
}
