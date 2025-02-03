<?php

namespace App\Http\Controllers;

use App\Models\CasaCampaña;
use App\Models\Directiva;
use App\Models\MiembrosCasaCampaña;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class CasaCampañaController extends Controller
{
    public function obtener()
    {
        return CasaCampaña::all();
    }
    public function obtenerMiembro($id)
    {
        return MiembrosCasaCampaña::where("CODCASACAMPANA", $id)->get();
    }

    public function obtenerMiembroOtra($id)
    {
        return MiembrosCasaCampaña::where("CODCASACAMPANA", "!=", $id)->get();
    }
    public function modificarCasaCampaña(Request $request, $id)
    {
        $casaCampaña = CasaCampaña::find($id);
        if (!$casaCampaña) {
            return response()->json(['error' => 'Casa no encontrada'], 404);
        }
        $casaCampaña->NOMBREENCARGADO = $request->NOMBREENCARGADO;
        $casaCampaña->UBICACION = $request->UBICACION;
        $casaCampaña->DIRECCION = $request->DIRECCION;
        $casaCampaña->CONTRASENACASA = Crypt::encryptString($request->CONTRASENACASA);
        $casaCampaña->CELULARCASA = $request->CELULARCASA;
        $casaCampaña->CORREOCASA = $request->CORREOCASA;
        $casaCampaña->ZONA = $request->ZONA;
        $casaCampaña->DISTRITO = $request->DISTRITO;
        $casaCampaña->NOMBREGRUPO = $request->NOMBREGRUPO;
        $casaCampaña->save();
        return 'Casa modificada exitosamente';
    }


public function modificarDirectivaCasaCampaña(Request $request, $id)
{
    // Buscar la casa de campaña
    $casaCampaña = CasaCampaña::find($id);
    if (!$casaCampaña) {
        return response()->json(['error' => 'Casa no encontrada'], 404);
    }

    // Actualizar la casa de campaña
    $casaCampaña->NOMBREENCARGADO = $request->NOMBREENCARGADO;
    $casaCampaña->save();

    // Buscar la directiva relacionada
    $directiva = Directiva::where('CODCASACAMPANA', $id)->first();

    if ($directiva) {
        // Si la directiva existe, actualizar los datos
        $directiva->NOMBRERESPONSABLELOGITICA = $request->NOMBRERESPONSABLELOGITICA;
        $directiva->NOMBRERESPONSABLEHACIENDA = $request->NOMBRERESPONSABLEHACIENDA;
        $directiva->NOMBRERESPONSABLEACTAS = $request->NOMBRERESPONSABLEACTAS;
        $directiva->NOMBRERESPONSABLEREDES = $request->NOMBRERESPONSABLEREDES;
        $directiva->NOMBRERESPONSABLEJUVENTUD = $request->NOMBRERESPONSABLEJUVENTUD;
        $directiva->save();

        $mensaje = "Directiva actualizada exitosamente";
    } else {
        // Si la directiva no existe, crear una nueva
        $directiva = new Directiva();
        $directiva->CODCASACAMPANA = $id;
        $directiva->NOMBRERESPONSABLELOGITICA = $request->NOMBRERESPONSABLELOGITICA;
        $directiva->NOMBRERESPONSABLEHACIENDA = $request->NOMBRERESPONSABLEHACIENDA;
        $directiva->NOMBRERESPONSABLEACTAS = $request->NOMBRERESPONSABLEACTAS;
        $directiva->NOMBRERESPONSABLEREDES = $request->NOMBRERESPONSABLEREDES;
        $directiva->NOMBRERESPONSABLEJUVENTUD = $request->NOMBRERESPONSABLEJUVENTUD;
        $directiva->save();

        $mensaje = "Directiva creada exitosamente";
    }

    return $mensaje;
}


    public function obtenerCasaCampaña($id)
    {
        $casaCampaña = CasaCampaña::find($id);
        if (!$casaCampaña) {
            return response()->json(['error' => 'Casa no encontrada'], 404);
        }

        try {
            $contraseñaCifrada = $casaCampaña->CONTRASENACASA;
            $contraseña = Crypt::decryptString($contraseñaCifrada);
            $casaCampaña->CONTRASENACASA = $contraseña;
        } catch (DecryptException $e) {
            return response()->json(['error' => 'Error al descifrar la contraseña'], 500);
        }
        $casaCampaña->directiva = Directiva::where('CODCASACAMPANA', $id)->first();
        return $casaCampaña;
    }


    public function AgregarCasaCampaña(Request $request)
    {

        $casa = new CasaCampaña();
        $casa->CODCASACAMPANA = $request->CODCASACAMPANA;
        $casa->NOMBREENCARGADO = $request->NOMBREENCARGADO;
        $casa->DIRECCION = $request->DIRECCION;
        $casa->UBICACION = $request->UBICACION;
        $casa->CONTRASENACASA = Crypt::encryptString($request->CONTRASENACASA);
        $casa->CELULARCASA = $request->CELULARCASA;
        $casa->CORREOCASA = $request->CORREOCASA;
        $casa->ZONA = $request->ZONA;
        $casa->DISTRITO = $request->DISTRITO;
        $casa->NOMBREGRUPO = $request->NOMBREGRUPO;
        $casa->save();
        return "correcto";
    }
    public function AgregarMiembro(Request $request)
    {

        $casa = new MiembrosCasaCampaña();
        $casa->CODCASACAMPANA = $request->CODCASACAMPANA;
        $casa->CELULARMIEMBRO = $request->CELULARMIEMBRO;
        $casa->NOMBREMIEMBRO = $request->NOMBREMIEMBRO;
        $casa->CORREOMIEMBRO = $request->CORREOMIEMBRO;
        $casa->CARGO = $request->CARGO;
        $casa->save();
        return "correcto";
    }
}
