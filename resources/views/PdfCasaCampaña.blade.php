<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Informe {{ $casa->NOMBREENCARGADO }}</title>
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
            <p><strong>Nombre:</strong> {{ $casa->NOMBREENCARGADO }}</p>
            <p><strong>Código:</strong> {{ $casa->CODCASACAMPANA }}</p>
            <p><strong>Cargo:</strong> Casa de campaña</p>
            <p><strong>Celular:</strong> {{ $casa->CELULARCASA }}</p>
            <p><strong>Fecha:</strong> {{ $fecha }}</p>
        </div>

        <div class="section">
            <h2>Solicitudes</h2>
            <table>
                <tr>
                    <th>Nº</th>
                    <th>Código de Solicitud</th>
                    <th>Evento</th>
                    <th>Descripción</th>
                    <th>Fecha Solicitada</th>
                    <th>Fecha Programada</th>
                    <th>Fecha Aprobada</th>
                    <th>Hora del Evento</th>
                    <th>Encargado</th>
                    <th>Estado</th>
                    <th>Observaciones</th>
                </tr>
                @foreach ($solicitudes as $solicitud)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $solicitud->CODSOLICITUD }}</td>
                        <td>{{ $solicitud->TIPOEVENTO }}</td>
                        <td>{{ $solicitud->DESCRIPCION }}</td>
                        <td>{{ $solicitud->FECHASOLICITADA }}</td>
                        <td>{{ $solicitud->FECHAPROGRAMADA }}</td>
                        <td>{{ $solicitud->FECHAAPROBADA }}</td>
                        <td>{{ $solicitud->HORAEVENTO }}</td>
                        <td>{{ $solicitud->NOMBREADMINISTRADOR }}</td>
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
                    <th>Código Encargado Logística</th>
                    <th>Encargado Logística</th>
                    <th>Código Encargado Redes</th>
                    <th>Encargado Redes</th>
                </tr>
                @foreach ($solicitudes as $solicitud)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $solicitud->CODSOLICITUD }}</td>
                        <td>{{ $solicitud->CODLOGISTICA }}</td>
                        <td>{{ $solicitud->NOMBRERESPONSABLE }}</td>
                        <td>{{ $solicitud->CODREDES }}</td>
                        <td>{{ $solicitud->NOMBREREDES }}</td>
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
                        <td>{{ $solicitud->CODLOGISTICA }}</td>
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
                    <div class="imagenes">
                        @foreach ($usuarios as $imagenesUsuario)
                            @foreach ($imagenesUsuario as $foto)
                                <img src="{{ $foto }}">
                            @endforeach
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</body>

</html>
