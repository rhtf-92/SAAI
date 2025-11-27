<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>@yield('title', 'Documento - SAAI')</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #333;
        }

        .page {
            width: 100%;
            padding: 20mm;
        }

        /* Header */
        .header {
            text-align: center;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 10px;
        }

        .header h1 {
            font-size: 18pt;
            color: #2563eb;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .header p {
            font-size: 9pt;
            color: #666;
        }

        /* Document Title */
        .document-title {
            text-align: center;
            margin: 30px 0;
        }

        .document-title h2 {
            font-size: 16pt;
            color: #1e40af;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .document-number {
            text-align: right;
            font-size: 9pt;
            color: #666;
            margin-bottom: 20px;
        }

        /* Content */
        .content {
            margin: 20px 0;
        }

        .content p {
            text-align: justify;
            margin-bottom: 15px;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table th {
            background-color: #f3f4f6;
            color: #374151;
            font-weight: bold;
            padding: 10px;
            text-align: left;
            border: 1px solid #d1d5db;
            font-size: 10pt;
        }

        table td {
            padding: 8px 10px;
            border: 1px solid #d1d5db;
            font-size: 10pt;
        }

        table tr:nth-child(even) {
            background-color: #f9fafb;
        }

        /* Info Box */
        .info-box {
            background-color: #f3f4f6;
            padding: 15px;
            border-left: 4px solid #2563eb;
            margin: 20px 0;
        }

        .info-box strong {
            color: #1e40af;
        }

        /* Footer / Signature */
        .signature-section {
            margin-top: 60px;
            text-align: center;
        }

        .signature-line {
            width: 250px;
            border-top: 2px solid #333;
            margin: 40px auto 10px;
        }

        .signature-name {
            font-weight: bold;
            font-size: 10pt;
        }

        .signature-title {
            font-size: 9pt;
            color: #666;
        }

        /* Footer */
        .footer {
            position: fixed;
            bottom: 10mm;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8pt;
            color: #999;
            padding: 10px 20mm;
            border-top: 1px solid #e5e7eb;
        }

        /* Page numbers */
        .page-number {
            position: fixed;
            bottom: 5mm;
            right: 20mm;
            font-size: 8pt;
            color: #999;
        }

        .page-number:before {
            content: "Página " counter(page);
        }
    </style>
</head>

<body>
    <div class="page">
        <!-- Header -->
        <div class="header">
            {{-- Logo placeholder - usuario debe agregar imagen en public/images/logo.png --}}
            {{-- <img src="{{ public_path('images/logo.png') }}" alt="Logo" class="logo"> --}}
            <h1>SAAI</h1>
            <p>Sistema Académico y Administrativo Institucional</p>
            <p style="font-size: 8pt;">Dirección de la Institución | Teléfono: (01) 123-4567 | Email: info@saai.edu.pe
            </p>
        </div>

        @yield('content')
    </div>

    <!-- Footer -->
    <div class="footer">
        Este documento es válido sin firma ni sello | Emitido el {{ now()->format('d/m/Y H:i') }}
    </div>

    <!-- Page number -->
    <div class="page-number"></div>
</body>

</html>