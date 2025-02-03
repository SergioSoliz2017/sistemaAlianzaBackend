<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImagenesController extends Controller
{
    public function uploadImage(Request $request)
    {
        $codUsuario = $request->input('codUsuario');
        $codSolicitud = $request->input('codSolicitud');
        $image = $request->file('image');

        $directory = "public/$codSolicitud/$codUsuario";
        if (!Storage::exists($directory)) {
            Storage::makeDirectory($directory);
        }
        $path = $image->store($directory);

        Storage::setVisibility($path, 'public');

        return response()->json([
            'message' => 'Imagen subida correctamente.',
            'path' => Storage::url($path), // URL pública de la imagen
        ]);
    }

    public function getImages(Request $request)
    {
        if (!$request->esAdmin) {
            $codSolicitud = $request->input('codSolicitud');
            $codUsuario = $request->input('codUsuario');
            $directory = "public/$codSolicitud/$codUsuario";

            // Verificar si el directorio existe
            if (!Storage::exists($directory)) {
                return response()->json([
                    'message' => 'No se encontraron imágenes.',
                    'images' => [],
                ]);
            }

            // Obtener todos los archivos del directorio
            $files = Storage::files($directory);

            // Generar URLs públicas para cada archivo
            $images = array_map(fn($file) => Storage::url($file), $files);

            return response()->json([
                'message' => 'Imágenes obtenidas con éxito.',
                'images' => array_map(fn($file) => url(Storage::url($file)), $files),
            ]);
        } else {
            $codSolicitud = $request->input('codSolicitud');
            $directory = "public/$codSolicitud";

            // Verificar si el directorio de la solicitud existe
            if (!Storage::exists($directory)) {
                return response()->json([
                    'message' => 'No se encontraron imágenes.',
                    'images' => [],
                ]);
            }

            // Obtener todos los subdirectorios (cada subdirectorio corresponde a un codUsuario)
            $subdirectories = Storage::directories($directory);
            $imagesByUser = [];

            foreach ($subdirectories as $subdirectory) {
                $codUsuario = basename($subdirectory); // Extraer el nombre del subdirectorio (codUsuario)
                $files = Storage::files($subdirectory);

                // Agregar imágenes del usuario al resultado
                $imagesByUser[$codUsuario] = array_map(fn($file) => url(Storage::url($file)), $files);
            }

            return response()->json([
                'message' => 'Imágenes obtenidas con éxito para el administrador.',
                'images' => $imagesByUser, // Todas las imágenes organizadas por codUsuario
            ]);
        }
    }
}
