@extends('reports.layout')

@section('title', 'Certificado de Estudios')

@section('content')
    <div class="document-number">
        N° {{ str_pad($student->id, 6, '0', STR_PAD_LEFT) }}-{{ date('Y') }}
    </div>

    <div class="document-title">
        <h2>CERTIFICADO DE ESTUDIOS</h2>
    </div>

    <div class="content">
        <p>
            La Dirección del <strong>Sistema Académico y Administrativo Institucional (SAAI)</strong>
            certifica que:
        </p>

        <div class="info-box">
            <p><strong>Estudiante:</strong> {{ $student->user->name ?? 'N/A' }}</p>
            <p><strong>Documento de Identidad:</strong> {{ $student->user->document_type ?? 'DNI' }} -
                {{ $student->user->document_number ?? 'N/A' }}</p>
            <p><strong>Código de Estudiante:</strong> {{ str_pad($student->id, 8, '0', STR_PAD_LEFT) }}</p>
            @if($student->enrollments->isNotEmpty())
                <p><strong>Programa Académico:</strong> {{ $student->enrollments->first()->plan->program->name ?? 'N/A' }}</p>
            @endif
        </div>

        <p>
            Ha cursado y aprobado las siguientes asignaturas en nuestra institución:
        </p>

        @if($grades->isNotEmpty())
            <table>
                <thead>
                    <tr>
                        <th style="width: 10%;">Código</th>
                        <th style="width: 45%;">Asignatura</th>
                        <th style="width: 15%;">Créditos</th>
                        <th style="width: 15%;">Nota</th>
                        <th style="width: 15%;">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($grades as $grade)
                        <tr>
                            <td>{{ $grade->course->code ?? 'N/A' }}</td>
                            <td>{{ $grade->course->name ?? 'N/A' }}</td>
                            <td style="text-align: center;">{{ $grade->course->credits ?? 0 }}</td>
                            <td style="text-align: center;">{{ number_format($grade->grade, 1) }}</td>
                            <td style="text-align: center;">
                                @if($grade->grade >= 11)
                                    <strong style="color: #10b981;">Aprobado</strong>
                                @else
                                    <strong style="color: #ef4444;">Desaprobado</strong>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="info-box">
                <p style="text-align: center; font-size: 12pt;">
                    <strong>PROMEDIO GENERAL:</strong> {{ number_format($average, 2) }}
                </p>
            </div>
        @else
            <p style="text-align: center; color: #666; font-style: italic; margin: 30px 0;">
                No se registran calificaciones para este estudiante.
            </p>
        @endif

        <p style="margin-top: 30px;">
            Se expide el presente certificado a solicitud del interesado para los fines que estime conveniente.
        </p>

        <p style="text-align: right; margin-top: 20px;">
            Lima, {{ $date }}
        </p>
    </div>

    <div class="signature-section">
        <div class="signature-line"></div>
        <p class="signature-name">Director Académico</p>
        <p class="signature-title">SAAI - Sistema Académico</p>
    </div>
@endsection