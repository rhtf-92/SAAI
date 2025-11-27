@extends('reports.layout')

@section('title', 'Constancia de Matrícula')

@section('content')
    <div class="document-number">
        N° {{ str_pad($enrollment->id, 6, '0', STR_PAD_LEFT) }}-{{ date('Y') }}
    </div>

    <div class="document-title">
        <h2>CONSTANCIA DE MATRÍCULA</h2>
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
            <p><strong>Programa Académico:</strong> {{ $enrollment->plan->program->name ?? 'N/A' }}</p>
            <p><strong>Plan de Estudios:</strong> {{ $enrollment->plan->name ?? 'N/A' }}</p>
            <p><strong>Periodo Académico:</strong> {{ $enrollment->term->name ?? 'N/A' }}</p>
        </div>

        <p>
            Se encuentra <strong style="color: #10b981;">MATRICULADO(A)</strong> en el periodo académico vigente,
            cursando las siguientes asignaturas:
        </p>

        @if($enrollment->enrollmentCourses->isNotEmpty())
            <table>
                <thead>
                    <tr>
                        <th style="width: 12%;">Código</th>
                        <th style="width: 50%;">Asignatura</th>
                        <th style="width: 13%;">Créditos</th>
                        <th style="width: 25%;">Sección</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($enrollment->enrollmentCourses as $enrollmentCourse)
                        <tr>
                            <td>{{ $enrollmentCourse->course->code ?? 'N/A' }}</td>
                            <td>{{ $enrollmentCourse->course->name ?? 'N/A' }}</td>
                            <td style="text-align: center;">{{ $enrollmentCourse->course->credits ?? 0 }}</td>
                            <td style="text-align: center;">{{ $enrollmentCourse->section ?? 'A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" style="text-align: right; font-weight: bold;">TOTAL DE CRÉDITOS:</td>
                        <td style="text-align: center; font-weight: bold; background-color: #dbeafe;">{{ $totalCredits }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        @else
            <p style="text-align: center; color: #666; font-style: italic; margin: 30px 0;">
                No se registran cursos matriculados para este periodo.
            </p>
        @endif

        <div class="info-box" style="margin-top: 20px;">
            <p><strong>Estado de Matrícula:</strong>
                @if($enrollment->status === 'active')
                    <span style="color: #10b981;">ACTIVA</span>
                @elseif($enrollment->status === 'inactive')
                    <span style="color: #6b7280;">INACTIVA</span>
                @else
                    <span style="color: #f59e0b;">{{ strtoupper($enrollment->status) }}</span>
                @endif
            </p>
            <p><strong>Fecha de Matrícula:</strong> {{ $enrollment->date ? $enrollment->date->format('d/m/Y') : 'N/A' }}</p>
        </div>

        <p style="margin-top: 30px;">
            Se expide la presente constancia a solicitud del interesado para los fines que estime conveniente.
        </p>

        <p style="text-align: right; margin-top: 20px;">
            Lima, {{ $date }}
        </p>
    </div>

    <div class="signature-section">
        <div class="signature-line"></div>
        <p class="signature-name">Director de Registro y Matrícula</p>
        <p class="signature-title">SAAI - Sistema Académico</p>
    </div>
@endsection