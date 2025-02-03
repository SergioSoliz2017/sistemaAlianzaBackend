<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

Route::get('/', function () {
    return "hola";
});

Route::get('/iniciarSesion',[App\Http\Controllers\AdministradorController::class,"Verificar"]);
Route::get('/obtenerCasaCampaña',[App\Http\Controllers\CasaCampañaController::class,"Obtener"]);
Route::get('/obtenerLogistica',[App\Http\Controllers\LogisticaController::class,"Obtener"]);
Route::get('/obtenerCasaCampaña/{id}',[App\Http\Controllers\CasaCampañaController::class,"obtenerCasaCampaña"]);
Route::get('/obtenerSolicitudes',[App\Http\Controllers\SolicitudController::class,"obtenerSolicitudes"]);
Route::get('/obtenerSolicitudes/{id}',[App\Http\Controllers\SolicitudController::class,"obtenerSolicitudesCasa"]);
Route::get('/obtenerRedes',[App\Http\Controllers\RedesController::class,"Obtener"]);
Route::get('/obtenerMiembros/{id}',[App\Http\Controllers\CasaCampañaController::class,"obtenerMiembro"]);
Route::get('/obtenerLogistica/{id}',[App\Http\Controllers\LogisticaController::class,"obtenerLogistica"]);
Route::get('/obtenerSolicitudesLogistica/{id}',[App\Http\Controllers\LogisticaController::class,"obtenerSolicitudes"]);
Route::get('/obtenerSolicitudesRedes/{id}',[App\Http\Controllers\RedesController::class,"obtenerSolicitudes"]);
Route::get('/obtenerRedes/{id}',[App\Http\Controllers\RedesController::class,"obtenerRedes"]);
Route::get('/obtenerAdministrador/{id}',[App\Http\Controllers\AdministradorController::class,"obtenerAdministrador"]);
Route::get('/solicitudesAdministrador/{id}',[App\Http\Controllers\SolicitudController::class,"solicitudesAdministrador"]);
Route::get('/solicitudesCasaCampaña/{id}',[App\Http\Controllers\SolicitudController::class,"solicitudesCasaCampaña"]);
Route::get('/obtenerMiembrosOtra/{id}',[App\Http\Controllers\CasaCampañaController::class,"obtenerMiembroOtra"]);
Route::get('/obtenerMiembrosAgregados/{id}', [App\Http\Controllers\ParticipantesController::class, 'obtenerMiembrosAgregados']);

Route::get('/cifrar/{id}',[App\Http\Controllers\AdministradorController::class,"cifrar"]);
Route::get('/obtenerImagenes',[App\Http\Controllers\ImagenesController::class,"getImages"]);

Route::post('/agregarCasaCampaña',[App\Http\Controllers\CasaCampañaController::class,"AgregarCasaCampaña"]);
Route::post('/agregarLogistica',[App\Http\Controllers\LogisticaController::class,"AgregarLogistica"]);
Route::post('/crearSolicitud',[App\Http\Controllers\SolicitudController::class,"crearSolicitud"]);
Route::post('/agregarRedes',[App\Http\Controllers\RedesController::class,"AgregarRedes"]);
Route::post('/asignarTarea',[App\Http\Controllers\TareasController::class,"AsignarTarea"]);
Route::post('/subirImagen',[App\Http\Controllers\ImagenesController::class,"uploadImage"]);
Route::post('/agregarMiembro',[App\Http\Controllers\CasaCampañaController::class,"AgregarMiembro"]);
Route::post('/subirLista', [App\Http\Controllers\ParticipantesController::class, 'subirLista']);

Route::put('/modificarCasaCampaña/{id}',[App\Http\Controllers\CasaCampañaController::class,"modificarCasaCampaña"]);
Route::put('/aceptarSolicitud/{id}',[App\Http\Controllers\SolicitudController::class,"aceptarSolicitud"]);
Route::put('/rechazarSolicitud/{id}',[App\Http\Controllers\SolicitudController::class,"rechazarSolicitud"]);
Route::put('/modificarLogistica/{id}',[App\Http\Controllers\LogisticaController::class,"modificarLogistica"]);
Route::put('/modificarRedes/{id}',[App\Http\Controllers\RedesController::class,"modificarRedes"]);
Route::put('/modificarDirectivaCasaCampaña/{id}',[App\Http\Controllers\CasaCampañaController::class,"modificarDirectivaCasaCampaña"]);

Route::get('obtenerPDF/{id}',[App\Http\Controllers\SolicitudController::class,"generarPDF"]);
Route::get('obtenerPDFCasa/{id}',[App\Http\Controllers\SolicitudController::class,"generarPDFCasa"]);
Route::get('obtenerPDFLogistica/{id}',[App\Http\Controllers\SolicitudController::class,"generarPDFLogistica"]);
Route::get('obtenerPDFRedes/{id}',[App\Http\Controllers\SolicitudController::class,"generarPDFRedes"]);
