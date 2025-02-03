<?php

namespace App\Http\Controllers;

use App\Models\Redes;
use App\Models\Solicitud;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class RedesController extends Controller
{
    public function obtener()
    {
        return Redes::all();
    }
    public function AgregarRedes(Request $request)
    {
        $redes = new Redes();
        $redes->CODREDES = $request->CODREDES;
        $redes->CELULARREDES = $request->CELULARREDES;
        $redes->NOMBREREDES = $request->NOMBREREDES;
        $redes->ENCARGADOREDES = $request->ENCARGADOREDES;
        $redes->CONTRASENAREDES = Crypt::encryptString($request->CONTRASENAREDES);
        $redes->save();
        return "guardado";
    }

    public function obtenerSolicitudes($id)
    {
        $solicitudes = Solicitud::join('tarearedes', 'solicitud.CODSOLICITUD', '=', 'tarearedes.CODSOLICITUD')
        ->leftJoin('administrador', 'solicitud.ACEPTO', '=', 'administrador.CODADMINISTRADOR')
        ->where('tarearedes.CODREDES', $id) // Eliminar el espacio extra
        ->with(['CasaCampañas', 'materiales']) // Cargar relaciones CasaCampañas y materialesDisponibles
        ->select(
            'solicitud.*', // Todos los campos de la tabla Solicitud
            'administrador.NOMBREADMINISTRADOR', // Campo específico de Administrador
            'administrador.CELULARADMINISTRADOR' // Campo específico de Administrador
        )->orderBy('FECHAPROGRAMADA', 'asc')->get();
        $nombre = Redes::find($id);
        $solicitudesConImagenes = $solicitudes->map(function ($solicitud, $index) use ($nombre) {
            // Preparar los datos para enviar a getImages
            $request = new \Illuminate\Http\Request();
            $request->replace([
                'esAdmin' => false, // Si quieres usar lógica de administrador
                'codSolicitud' => $solicitud['CODSOLICITUD'],
                'codUsuario' => $nombre->NOMBREREDES,
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

    public function obtenerRedes($id)
    {
        $redes = Redes::find($id); // Busca por el ID
    if (!$redes) {
        return response()->json(['error' => 'Casa no encontrada'], 404);
    }
        try {
            $contraseñaCifrada = $redes->CONTRASENAREDES;
        $contraseña = Crypt::decryptString($contraseñaCifrada);
        $redes->CONTRASENAREDES = $contraseña;
        } catch (DecryptException $e) {
            return response()->json(['error' => 'Error al descifrar la contraseña'], 500);
        }
        return $redes;
    }
    public function modificarRedes(Request $request, $id)
    {
        $redes = Redes::find($id);
        if (!$redes) {
            return response()->json(['error' => 'Redes no encontrada'], 404);
        }
        $redes->NOMBREREDES = $request->NOMBREREDES;
        $redes->ENCARGADOREDES = $request->ENCARGADOREDES;
        $redes->CELULARREDES = $request->CELULARREDES;
        $redes->CONTRASENAREDES = Crypt::encryptString($request->CONTRASENAREDES);
        
        $redes->save();
        return 'Redes modificada exitosamente';
    }
}
