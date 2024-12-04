<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitación a Subasta</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            /* Color de fondo suave */
            margin: 0;
            padding: 20px;
            /* Separación del contenido del borde */
            display: flex;
            justify-content: center;
        }

        .email-container {
            max-width: 600px;
            background-color: #ffffff;
            /* Fondo blanco para enmarcar el contenido */
            border-radius: 8px;
            /* Bordes redondeados */
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            /* Sombra ligera */
        }

        .header {
            text-align: center;
            background-color: #f1f1f1;
            /* Fondo claro para el header */
            padding: 20px;
        }

        .content {
            padding: 20px;
            line-height: 1.6;
            color: #333333;
            /* Color de texto más oscuro para mejor legibilidad */
        }

        .footer {
            text-align: center;
            color: #888888;
            background-color: #f1f1f1;
            /* Fondo claro para el footer */
            padding: 10px;
            font-size: 12px;
        }

        a {
            color: #007bff;
            /* Azul para los enlaces */
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <img src="{{ $owner['logo'] }}" alt="Logo de {{ $owner['company_name'] }}" style="max-height: 100px;">
        </div>
        <div class="content">
            <p>Buenas {{ $user['nom_cliweb'] }},</p>
            <p>Ha sido invitado a participar en la subasta <strong>{{ $auction['des_sub'] }}</strong>.</p>
            <p>
                Puede acceder a los lotes desde el siguiente enlace:
                <a href="{{ $auction['link'] }}">{{ $auction['des_sub'] }}</a>.
            </p>
            <p>Saludos cordiales,</p>
            <p>{{ $owner['company_name'] }}</p>
        </div>
        <div class="footer">
            <p>&copy; {{ now()->year }} {{ $owner['company_name'] }}. Todos los derechos reservados.</p>
        </div>
    </div>
</body>

</html>
