<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Informe {{ $administrador->NOMBREADMINISTRADOR }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .container {
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            background: #6603b2;
            color: white;
            padding: 10px;
            border-radius: 5px;
        }


        .section {
            padding: 10px;
            background: #f9f9f9;
            border-radius: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 10px;
            text-align: center;
        }

        th {
            background: #6603b2;
            color: white;
        }

        .info {
            margin-bottom: 20px;
            padding: 10px;
            background: #f9f9f9;
            border-radius: 5px;
            clear: both;
            box-sizing: border-box;
            width: 100%;
        }

        .imagenes {
            display: block;
            width: 100%;
            overflow: hidden;
        }

        img {
            width: 300px;
            height: 300px;
            margin: 5px;
            border-radius: 5px;
            display: inline-block;
            box-sizing: border-box;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>INFORME</h1>
        <div class="info">
            <p><strong>Nombre:</strong> {{ $administrador->NOMBREADMINISTRADOR }}</p>
            <p><strong>Código:</strong> {{ $administrador->CODADMINISTRADOR }}</p>
            <p><strong>Cargo:</strong> Administrador</p>
            <p><strong>Celular:</strong> {{ $administrador->CELULARADMINISTRADOR }}</p>
            <p><strong>Fecha:</strong> {{ $fecha }}</p>
        </div>
        <div class="section">
            <h2>Solicitudes</h2>
            <table>
                <tr>
                    <th>Nº</th>
                    <th>Código de Solicitud</th>
                    <th>Casa de Campaña</th>
                    <th>Nombre Encargado</th>
                    <th>Evento</th>
                    <th>Descripción</th>
                    <th>Fecha Solicitada</th>
                    <th>Fecha Programada</th>
                    <th>Fecha Aprobada</th>
                    <th>Hora del Evento</th>
                    <th>Estado</th>
                    <th>Observaciones</th>
                </tr>
                @foreach ($solicitudes as $solicitud)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $solicitud->CODSOLICITUD }}</td>
                        <td>{{ $solicitud->CasaCampañas[0]->CODCASACAMPANA }}</td>
                        <td>{{ $solicitud->CasaCampañas[0]->NOMBREENCARGADO }}</td>
                        <td>{{ $solicitud->TIPOEVENTO }}</td>
                        <td>{{ $solicitud->DESCRIPCION }}</td>
                        <td>{{ $solicitud->FECHASOLICITADA }}</td>
                        <td>{{ $solicitud->FECHAPROGRAMADA }}</td>
                        <td>{{ $solicitud->FECHAAPROBADA }}</td>
                        <td>{{ $solicitud->HORAEVENTO }}</td>
                        <td>{{ $solicitud->ESTADO }}</td>
                        <td>{{ $solicitud->OBSERVACIONES }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="section">
            <h2>Asignados</h2>
            <table>
                <tr>
                    <th>Nº</th>
                    <th>Código de Solicitud</th>
                    <th>Casa de Campaña</th>
                    <th>Código Encargado LOGISTICA</th>
                    <th>Encargado Logistica</th>
                    <th>Código Encargado Redes</th>
                    <th>Encargado Redes</th>
                </tr>
                @foreach ($solicitudes as $solicitud)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $solicitud->CODSOLICITUD }}</td>
                        <td>{{ $solicitud->CasaCampañas[0]->CODCASACAMPANA }}</td>
                        <td>{{ $solicitud->tareaLogistica->CODLOGISTICA ?? 'N/A' }}</td>
                        <td>{{ $solicitud->tareaLogistica->logistica->NOMBRERESPONSABLE ?? 'N/A' }}</td>
                        <td>{{ $solicitud->tareaRedes->CODREDES ?? 'N/A' }}</td>
                        <td>{{ $solicitud->tareaRedes->redes->NOMBREREDES ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="section">
            <h2>Material solicitado / entregado</h2>
            <table>
                <tr>
                    <th>Nº</th>
                    <th>Código de Solicitud</th>
                    <th>Código Encargado Logística</th>
                    <th>Material</th>
                </tr>
                @foreach ($solicitudes as $solicitud)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $solicitud->CODSOLICITUD }}</td>
                        <td>{{ $solicitud->tareaLogistica->CODLOGISTICA ?? 'N/A' }}</td>
                        <td>
                            @foreach ($solicitud->materiales as $material)
                                <p>{{ $material->CANTIDADPEDIDA }} {{ $material->MATERIALPEDIDO }}</p>
                            @endforeach
                        </td>

                    </tr>
                @endforeach
            </table>
        </div>
        <div class="section">
            <h2>Imágenes</h2>
            @foreach ($imagenes as $codSolicitud => $usuarios)
                <div class="info">
                    <p><strong>Solicitud:</strong> {{ $codSolicitud }}</p>
                    @foreach ($usuarios as $usuario => $fotos)
                        <p><strong>Usuario:</strong> {{ $usuario }}</p>
                        <div class="imagenes">
                            @foreach ($fotos as $foto)
                                <img src="{{ $foto }}">
                            @endforeach
                        </div>
                    @endforeach

                </div>
            @endforeach
        </div>
    </div>
</body>

</html>
