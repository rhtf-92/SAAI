<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SAAI - Notificación')</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .email-wrapper {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            background: linear-gradient(135deg, #2563eb, #1e40af);
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }

        .email-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }

        .email-header p {
            margin: 5px 0 0;
            font-size: 14px;
            opacity: 0.9;
        }

        .email-body {
            padding: 40px 30px;
        }

        .email-body h2 {
            color: #2563eb;
            margin-top: 0;
        }

        .email-button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #2563eb;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
            font-weight: bold;
        }

        .email-footer {
            background-color: #f9fafb;
            padding: 20px 30px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
        }

        .email-footer a {
            color: #2563eb;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="email-wrapper">
        <!-- Header -->
        <div class="email-header">
            <h1>SAAI</h1>
            <p>Sistema Académico y Administrativo Institucional</p>
        </div>

        <!-- Body -->
        <div class="email-body">
            @yield('content')
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p>
                Este es un correo automático, por favor no responder.<br>
                Si tienes alguna consulta, contáctanos en <a href="mailto:soporte@saai.edu.pe">soporte@saai.edu.pe</a>
            </p>
            <p style="margin-top: 15px;">
                &copy; {{ date('Y') }} SAAI. Todos los derechos reservados.
            </p>
        </div>
    </div>
</body>

</html>