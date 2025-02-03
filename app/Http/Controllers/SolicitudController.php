<?php

namespace App\Http\Controllers;

use App\Models\Administrador;
use App\Models\CasaCampaña;
use App\Models\Logistica;
use App\Models\MaterialPedido;
use App\Models\Redes;
use App\Models\Solicitud;
use App\Models\TareaLogistica;
use App\Models\TareaRedes;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SolicitudController extends Controller
{
    public function obtenerSolicitudes()
    {
        $solicitudes = Solicitud::with(['CasaCampañas', 'materiales'])
            ->orderBy('FECHASOLICITADA', 'desc') // Ordenar por fecha descendente
            ->get();

        $resultado = $solicitudes->map(function ($solicitud) {
            // Buscar en TareaLogistica por el CODSOLICITUD
            $tareaLogistica = TareaLogistica::where('CODSOLICITUD', $solicitud->CODSOLICITUD)->first();
            $nombreResponsable = null;
            $celularLogistica = null;

            // Si se encuentra la tarea logística, buscar en Logistica por el CODLOGISTICA
            if ($tareaLogistica) {
                $logistica = Logistica::where('CODLOGISTICA', $tareaLogistica->CODLOGISTICA)->first();
                $nombreResponsable = $logistica ? $logistica->NOMBRERESPONSABLE : null;
                $celularLogistica = $logistica ? $logistica->CELULARLOGISTICA : null; // Obtener CELULARLOGISTICA
            }
            $tareaRedes = TareaRedes::where('CODSOLICITUD', $solicitud->CODSOLICITUD)->first();
            $nombreResponsableR = null;
            $celularRedes = null;

            if ($tareaRedes) {
                $redes = Redes::where('CODREDES', $tareaRedes->CODREDES)->first();
                $nombreResponsableR = $redes ? $redes->NOMBREREDES : null;
                $celularRedes = $redes ? $redes->CELULARREDES : null; // Obtener CELULARREDES
            }
            $administrador = Administrador::where('CODADMINISTRADOR', $solicitud->ACEPTO)->first();
            $nombreAdministrador = $administrador ? $administrador->NOMBREADMINISTRADOR : null;
            $celularAdministrador = $administrador ? $administrador->CELULARADMINISTRADOR : null;

            return [
                'CODSOLICITUD' => $solicitud->CODSOLICITUD,
                'DESCRIPCION' => $solicitud->DESCRIPCION,
                'FECHASOLICITADA' => $solicitud->FECHASOLICITADA,
                'FECHAPROGRAMADA' => $solicitud->FECHAPROGRAMADA,
                'TIPOEVENTO' => $solicitud->TIPOEVENTO,
                'HORAEVENTO' => $solicitud->HORAEVENTO,
                'OBSERVACIONES' => $solicitud->OBSERVACIONES,
                'ACEPTO' => $solicitud->ACEPTO,
                'ESTADO' => $solicitud->ESTADO,
                'UBICACIONEVENTO' => $solicitud->UBICACIONEVENTO,
                'FECHAAPROBADA' => $solicitud->FECHAAPROBADA,
                'encargadoLogistica' => $nombreResponsable, // Nombre encargado de logística
                'celularLogistica' => $celularLogistica, // Celular encargado de logística
                'encargadoRedes' => $nombreResponsableR, // Nombre encargado de redes
                'celularRedes' => $celularRedes, // Celular encargado de redes
                'nombreAdministrador' => $nombreAdministrador, // Nombre administrador
                'celularAdministrador' => $celularAdministrador, // Celular administrador
                'casa_campañas' => $solicitud->CasaCampañas->map(function ($casaCampaña) {
                    return [
                        'CODCASACAMPANA' => $casaCampaña->CODCASACAMPANA,
                        'NOMBREENCARGADO' => $casaCampaña->NOMBREENCARGADO,
                        'DIRECCION' => $casaCampaña->DIRECCION,
                        'UBICACION' => $casaCampaña->UBICACION,
                        'CONTRASENACASA' => $casaCampaña->CONTRASENACASA,
                        'CELULARCASA' => $casaCampaña->CELULARCASA,
                        'CORREOCASA' => $casaCampaña->CORREOCASA,
                        'pivot' => [
                            'CODSOLICITUD' => $casaCampaña->pivot->CODSOLICITUD,
                            'CODCASACAMPANA' => $casaCampaña->pivot->CODCASACAMPANA,
                        ],
                    ];
                }),
                'materiales' => $solicitud->materiales->map(function ($material) {
                    return [
                        'CODSOLICITUD' => $material->CODSOLICITUD,
                        'MATERIALPEDIDO' => $material->MATERIALPEDIDO,
                        'CANTIDADPEDIDA' => $material->CANTIDADPEDIDA,
                    ];
                }),
            ];
        });

        return $resultado;
    }

    public function aceptarSolicitud($id, $encargado, $fecha)
    {
        $solicitud = Solicitud::find($id);
        $solicitud->FECHAAPROBADA = $fecha;
        $solicitud->ESTADO = "Aceptado";
        $solicitud->ACEPTO = $encargado;
        $solicitud->save();
        return "Aceptado";
    }
    public function rechazarSolicitud($id, Request $request)
    {
        $solicitud = Solicitud::find($id);
        $solicitud->ESTADO = "Rechazado";
        $solicitud->ACEPTO = $request->ACEPTO;
        $solicitud->OBSERVACIONES = $request->OBSERVACIONES;
        $solicitud->FECHAAPROBADA = $request->FECHAAPROBADA;

        $solicitud->save();
        return "Rechazado";
    }
    public function obtenerSolicitudesCasa($codCasaCampaña)
    {
        $solicitudes = CasaCampaña::with(['Solicitudes.materiales'])
            ->where('CODCASACAMPANA', $codCasaCampaña)
            ->get(['CODCASACAMPANA', 'NOMBREENCARGADO', 'DIRECCION', 'UBICACION', 'CONTRASENACASA', 'CELULARCASA', 'CORREOCASA']);

        $resultado = $solicitudes->flatMap(function ($casaCampaña) {
            return $casaCampaña->Solicitudes->map(function ($solicitud) use ($casaCampaña) {
                $tareaLogistica = TareaLogistica::where('CODSOLICITUD', $solicitud->CODSOLICITUD)->first();
                $nombreResponsable = null;
                $celularLogistica = null;
                $codResponsable = null;
                // Si se encuentra la tarea logística, buscar en Logistica por el CODLOGISTICA
                if ($tareaLogistica) {
                    $logistica = Logistica::where('CODLOGISTICA', $tareaLogistica->CODLOGISTICA)->first();
                    $nombreResponsable = $logistica ? $logistica->NOMBRERESPONSABLE : null;
                    $codResponsable = $tareaLogistica->CODLOGISTICA;
                    $celularLogistica = $logistica ? $logistica->CELULARLOGISTICA : null; // Obtener CELULARLOGISTICA
                }
                $tareaRedes = TareaRedes::where('CODSOLICITUD', $solicitud->CODSOLICITUD)->first();
                $nombreResponsableR = null;
                $celularRedes = null;
                $codResponsableR = null;
                if ($tareaRedes) {
                    $redes = Redes::where('CODREDES', $tareaRedes->CODREDES)->first();
                    $nombreResponsableR = $redes ? $redes->NOMBREREDES : null;
                    $codResponsableR = $tareaRedes->CODREDES;
                    $celularRedes = $redes ? $redes->CELULARREDES : null; // Obtener CELULARREDES
                }
                $administrador = Administrador::where('CODADMINISTRADOR', $solicitud->ACEPTO)->first();
                $nombreAdministrador = $administrador ? $administrador->NOMBREADMINISTRADOR : null;
                $celularAdministrador = $administrador ? $administrador->CELULARADMINISTRADOR : null;

                return [
                    'CODSOLICITUD' => $solicitud->CODSOLICITUD,
                    'DESCRIPCION' => $solicitud->DESCRIPCION,
                    'FECHASOLICITADA' => $solicitud->FECHASOLICITADA,
                    'FECHAPROGRAMADA' => $solicitud->FECHAPROGRAMADA,
                    'TIPOEVENTO' => $solicitud->TIPOEVENTO,
                    'HORAEVENTO' => $solicitud->HORAEVENTO,
                    'OBSERVACIONES' => $solicitud->OBSERVACIONES,
                    'ACEPTO' => $solicitud->ACEPTO,
                    'ESTADO' => $solicitud->ESTADO,
                    'FECHAAPROBADA' => $solicitud->FECHAAPROBADA,
                    'UBICACIONEVENTO' => $solicitud->UBICACIONEVENTO,
                    'casa_campañas' => [
                        [
                            'CODCASACAMPANA' => $casaCampaña->CODCASACAMPANA,
                            'NOMBREENCARGADO' => $casaCampaña->NOMBREENCARGADO,
                            'DIRECCION' => $casaCampaña->DIRECCION,
                            'UBICACION' => $casaCampaña->UBICACION,
                            'CONTRASENACASA' => $casaCampaña->CONTRASENACASA,
                            'CELULARCASA' => $casaCampaña->CELULARCASA,
                            'CORREOCASA' => $casaCampaña->CORREOCASA,
                            'pivot' => [
                                'CODSOLICITUD' => $solicitud->CODSOLICITUD,
                                'CODCASACAMPANA' => $casaCampaña->CODCASACAMPANA,
                            ]
                        ]
                    ],
                    'codLogistica' => $codResponsable,
                    'codRedes' => $codResponsableR,
                    'encargadoLogistica' => $nombreResponsable, // Nombre encargado de logística
                    'celularLogistica' => $celularLogistica, // Celular encargado de logística
                    'encargadoRedes' => $nombreResponsableR, // Nombre encargado de redes
                    'celularRedes' => $celularRedes, // Celular encargado de redes
                    'nombreAdministrador' => $nombreAdministrador, // Nombre administrador
                    'celularAdministrador' => $celularAdministrador,
                    'materiales' => $solicitud->materiales->map(function ($material) {
                        return [
                            'CODSOLICITUD' => $material->CODSOLICITUD,
                            'MATERIALPEDIDO' => $material->MATERIALPEDIDO,
                            'CANTIDADPEDIDA' => $material->CANTIDADPEDIDA,
                        ];
                    })
                ];
            });
        });
        $solicitudesConImagenes = $resultado->map(function ($solicitud) {
            // Preparar los datos para enviar a getImages
            $request = new \Illuminate\Http\Request();
            $request->replace([
                'esAdmin' => false, // Si quieres usar lógica de administrador
                'codSolicitud' => $solicitud['CODSOLICITUD'],
                'codUsuario' => $solicitud['casa_campañas'][0]['NOMBREENCARGADO'],
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

    public function crearSolicitud(Request $request)
    {
        try {
            $solicitud = new Solicitud();
            $solicitud->CODSOLICITUD = $request->CODSOLICITUD;
            $solicitud->DESCRIPCION = $request->DESCRIPCION;
            $solicitud->FECHASOLICITADA = $request->FECHASOLICITADA;
            $solicitud->FECHAPROGRAMADA = $request->FECHAPROGRAMADA;
            $solicitud->TIPOEVENTO = $request->TIPOEVENTO;
            $solicitud->ESTADO = $request->ESTADO;
            $solicitud->HORAEVENTO = $request->HORAEVENTO;
            $solicitud->UBICACIONEVENTO = $request->UBICACIONEVENTO;
            $solicitud->save();
            $solicitud->CasaCampañas()->attach($request->CODCASACAMPANA);
            foreach ($request->MATERIALES as $materia) {
                $materialN = new MaterialPedido();
                $materialN->CODSOLICITUD = $request->CODSOLICITUD;
                $materialN->MATERIALPEDIDO = $materia['nombre'];
                $materialN->CANTIDADPEDIDA = $materia['cantidad'];
                $materialN->save();
            }
            return "hecho";
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear la solicitud.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function solicitudesAdministrador($id)
    {
        // Obtener las solicitudes con sus relaciones
        $solicitudes = Solicitud::with([
            'CasaCampañas',
            'materiales',
            'tareaLogistica',
            'tareaRedes'
        ])->where('ACEPTO', $id)->get();

        // Iterar sobre las solicitudes para agregar imágenes
        $solicitudesConImagenes = $solicitudes->map(function ($solicitud) {
            // Preparar los datos para enviar a getImages
            $request = new \Illuminate\Http\Request();
            $request->replace([
                'esAdmin' => true, // Si quieres usar lógica de administrador
                'codSolicitud' => $solicitud->CODSOLICITUD,
            ]);

            // Llamar al método getImages del controlador ImagenesController
            $imagenesController = app(\App\Http\Controllers\ImagenesController::class);
            $response = $imagenesController->getImages($request);

            // Decodificar el JSON de la respuesta
            $imagenes = $response->getData()->images ?? [];

            // Agregar las imágenes a la solicitud
            $solicitudArray = $solicitud->toArray();
            $solicitudArray['imagenes'] = $imagenes;

            return $solicitudArray;
        });

        // Retornar las solicitudes con imágenes
        return response()->json($solicitudesConImagenes);
    }


    public function generarPDF($id)
    {
        $administrador = Administrador::find($id);
        $solicitudes = Solicitud::with([
            'CasaCampañas',
            'materiales',
            'tareaLogistica',
            'tareaRedes'
        ])->where('ACEPTO', $id)->orderBy('FECHASOLICITADA', 'desc')
            ->get();
        $imagenes = [];
        foreach ($solicitudes as $solicitud) {
            $codSolicitud = $solicitud->CODSOLICITUD;
            $tipoEvento = strtoupper($solicitud->TIPOEVENTO); 
            $directory = "public/$codSolicitud";

            if (!Storage::exists($directory)) {
                continue;
            }

            $subdirectories = Storage::directories($directory);
            $imagesByUser = [];

            foreach ($subdirectories as $subdirectory) {
                $codUsuario = basename($subdirectory);
                $files = Storage::files($subdirectory);
                $imagesByUser[$codUsuario] = array_map(fn($file) => public_path('storage/' . str_replace('public/', '', $file)), $files);
            }
            $imagenes["$codSolicitud - $tipoEvento"] = $imagesByUser;
        }
        $fecha = now()->format('d/m/Y'); 
        $pdf = PDF::loadView('Pdf', [
            'administrador' => $administrador,
            'solicitudes' => $solicitudes,
            'imagenes' => $imagenes,
            'fecha' => $fecha,
        ])->setPaper('a2', 'landscape');
        return $pdf->stream();
    }

    public function generarPDFCasa($id)
    {
        $solicitudes = Solicitud::with(['CasaCampañas', 'materiales'])
            ->leftJoin('administrador', 'solicitud.ACEPTO', '=', 'administrador.CODADMINISTRADOR')
            ->leftJoin('tarealogistica', 'solicitud.CODSOLICITUD', '=', 'tarealogistica.CODSOLICITUD')
            ->leftJoin('logistica', 'tarealogistica.CODLOGISTICA', '=', 'logistica.CODLOGISTICA')
            ->leftJoin('tarearedes', 'solicitud.CODSOLICITUD', '=', 'tarearedes.CODSOLICITUD')
            ->leftJoin('redes', 'tarearedes.CODREDES', '=', 'redes.CODREDES')
            ->orderBy('FECHASOLICITADA', 'desc')
            ->get([
                'solicitud.*',
                'administrador.NOMBREADMINISTRADOR as NOMBREADMINISTRADOR',
                'logistica.*',
                'redes.*' 
            ]);
        $casa = CasaCampaña::find($id);
        $imagenes = [];
        foreach ($solicitudes as $solicitud) {
            $codSolicitud = $solicitud->CODSOLICITUD;
            $codUsuario = $casa->NOMBREENCARGADO;
            $directory = "public/$codSolicitud/$codUsuario";
            $tipoEvento = strtoupper($solicitud->TIPOEVENTO); 
            if (!Storage::exists($directory)) {
                continue;
            }
            $files = Storage::files($directory);
            $imagenes["$codSolicitud - $tipoEvento"] = [ array_map(fn($file) => public_path('storage/' . str_replace('public/', '', $file)), $files)
            ];
        }
        $fecha = now()->setTimezone('America/La_Paz')->format('d/m/Y');
        $pdf = PDF::loadView('PdfCasaCampaña', [
            'casa' => $casa,
            'solicitudes' => $solicitudes,
            'fecha' => $fecha,
            'imagenes' => $imagenes,
        ])->setPaper('a2', 'landscape');
        return $pdf->stream();
    }
    public function generarPDFLogistica($id)
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
        $logistica = Logistica::find($id);
        $imagenes = [];
        foreach ($solicitudes as $solicitud) {
            $codSolicitud = $solicitud->CODSOLICITUD;
            $codUsuario = $logistica->NOMBRERESPONSABLE;
            $directory = "public/$codSolicitud/$codUsuario";
            $tipoEvento = strtoupper($solicitud->TIPOEVENTO); 
            if (!Storage::exists($directory)) {
                continue;
            }
            $files = Storage::files($directory);
            $imagenes["$codSolicitud - $tipoEvento"] = [ array_map(fn($file) => public_path('storage/' . str_replace('public/', '', $file)), $files)
            ];
        }
        $fecha = now()->setTimezone('America/La_Paz')->format('d/m/Y');
        $pdf = PDF::loadView('PdfLogistica', [
            'logistica' => $logistica,
            'solicitudes' => $solicitudes,
            'fecha' => $fecha,
            'imagenes' => $imagenes,
        ])->setPaper('a2', 'landscape');
        return $pdf->stream();
    }
    public function generarPDFRedes($id)
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
        $redes = Redes::find($id);
        $imagenes = [];
        foreach ($solicitudes as $solicitud) {
            $codSolicitud = $solicitud->CODSOLICITUD;
            $codUsuario = $redes->NOMBREREDES;
            $directory = "public/$codSolicitud/$codUsuario";
            $tipoEvento = strtoupper($solicitud->TIPOEVENTO); 
            if (!Storage::exists($directory)) {
                continue;
            }
            $files = Storage::files($directory);
            $imagenes["$codSolicitud - $tipoEvento"] = [ array_map(fn($file) => public_path('storage/' . str_replace('public/', '', $file)), $files)
            ];
        }
        $fecha = now()->setTimezone('America/La_Paz')->format('d/m/Y');
        $pdf = PDF::loadView('PdfRedes', [
            'redes' => $redes,
            'solicitudes' => $solicitudes,
            'fecha' => $fecha,
            'imagenes' => $imagenes,
        ])->setPaper('a2', 'landscape');
        return $pdf->stream();
    }
}
