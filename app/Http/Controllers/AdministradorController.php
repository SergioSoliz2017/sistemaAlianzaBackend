<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Administrador;
use App\Models\CasaCampaña;
use App\Models\Logistica;
use App\Models\Redes;
use Illuminate\Support\Facades\Crypt;

class AdministradorController extends Controller
{
    public function show()
    {
        return Administrador::all();
    }
    public function obtenerAdministrador($id)
    {
        return Administrador::find($id);
    }
    public function Verificar(Request $request)
    {
        $codigo = $request->CODIGO;
        $contraseña = $request->CONTRASEÑA;
        
        $admin = Administrador::where('CODADMINISTRADOR', $codigo)->first();
        if ($admin) {
            $contraseñaCifrada = $admin->CONTRASENA;
            if ($contraseña === Crypt::decryptString($contraseñaCifrada)) {
                return "Administrador";
            }
            return "Contraseña incorrecta";
        }
        $casaCampaña = CasaCampaña::where('CODCASACAMPANA', $codigo)->first();
        if ($casaCampaña) {
            $contraseñaCifrada = $casaCampaña->CONTRASENACASA;
            if ($contraseña === Crypt::decryptString($contraseñaCifrada)) {
                return "CasaCampaña";
            }
            return "Contraseña incorrecta";
        }
        $logistica = Logistica::where('CODLOGISTICA', $codigo)->first();
        if ($logistica) {
            $contraseñaCifrada = $logistica->CONTRASENALOGISTICA;
            if ($contraseña === Crypt::decryptString($contraseñaCifrada)) {
                return "Logistica";
            }
            return "Contraseña incorrecta";
        }
        $redes = Redes::where('CODREDES', $codigo)->first();
        if ($redes) {
            $contraseñaCifrada = $redes->CONTRASENAREDES;
            if ($contraseña === Crypt::decryptString($contraseñaCifrada)) {
                return "Redes";
            }
            return "Contraseña incorrecta";
        }
        return "Usuario no registrado.";
    }


    public function cifrar($id)
    {
        return Crypt::encryptString($id);
    }
    
}
