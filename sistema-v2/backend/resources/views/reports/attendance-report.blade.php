@extends('reports.layout')

@section('title', 'Reporte de Asistencia')

@section('content')
    <div class="document-number">
        N° {{ str_pad($course->id, 6, '0', STR_PAD_LEFT) }}-{{ date('Y') }}
    </div>

    <div class="document-title">
        <h2>REPORTE DE ASISTENCIA</h2>
    </div>

    <div class="content">
        <div class="info-box">
            <p><strong>Curso:</strong> {{ $course->name ?? 'N/A' }}</p>
            <p><strong>Código:</strong> {{ $course->code ?? 'N/A' }}</p>
            @if(isset($term))
                <p><strong>Periodo:</strong> {{ $term->name ?? 'N/A' }}</p>
            @endif
            @if($startDate && $endDate)
                <p><strong>Periodo de Reporte:</strong> {{ $startDate }} - {{ $endDate }}</p>
            @endif
            <p><strong>Total de Sesiones:</strong> {{ count($dates) }}</p>
        </div>

        @if(count($students) > 0 && count($dates) > 0)
            <table style="font-size: 9pt;">
                <thead>
                    <tr>
                        <th style="width: 8%;">Código</th>
                        <th style="width: 25%;">Estudiante</th>
                        @foreach($dates as $date)
                            <th style="width: {{ 67 / count($dates) }}%; text-align: center;">
                                {{ \Carbon\Carbon::parse($date)->format('d/m') }}
                            </th>
                        @endforeach
                        <th style="width: 8%; text-align: center;">%</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $studentData)
                        <tr>
                            <td>{{ str_pad($studentData['student']->id, 8, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $studentData['student']->user->name ?? 'N/A' }}</td>
                            @foreach($dates as $date)
                                @php
                                    $attendance = $studentData['attendances']->firstWhere('date', $date);
                                    $status = $attendance ? $attendance->status : '-';
                                    $symbol = match ($status) {
                                        'present' => '✓',
                                        'absent' => '✗',
                                        'late' => 'T',
                                        'justified' => 'J',
                                        default => '-'
                                    };
                                    $color = match ($status) {
                                        'present' => '#10b981',
                                        'absent' => '#ef4444',
                                        'late' => '#f59e0b',
                                        'justified' => '#3b82f6',
                                        default => '#999'
                                    };
                                @endphp
                                <td style="text-align: center; color: {{ $color }}; font-weight: bold;">
                                    {{ $symbol }}
                                </td>
                            @endforeach
                            <td
                                style="text-align: center; font-weight: bold; 
                                            background-color: {{ $studentData['percentage'] >= 85 ? '#d1fae5' : ($studentData['percentage'] >= 70 ? '#fef3c7' : '#fee2e2') }};">
                                {{ number_format($studentData['percentage'], 1) }}%
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="info-box" style="margin-top: 30px;">
                <p style="font-size: 10pt;"><strong>Leyenda:</strong></p>
                <p style="font-size: 9pt;">
                    <span style="color: #10b981; font-weight: bold;">✓</span> Presente |
                    <span style="color: #ef4444; font-weight: bold;">✗</span> Ausente |
                    <span style="color: #f59e0b; font-weight: bold;">T</span> Tarde |
                    <span style="color: #3b82f6; font-weight: bold;">J</span> Justificado
                </p>
                <p style="font-size: 9pt; margin-top: 10px;">
                    <span style="background-color: #d1fae5; padding: 3px 6px;">≥ 85%</span> Normal |
                    <span style="background-color: #fef3c7; padding: 3px 6px;">70-84%</span> Observación |
                    <span style="background-color: #fee2e2; padding: 3px 6px;">&lt; 70%</span> Crítico
                </p>
            </div>

            <!-- Estadísticas -->
            <div style="margin-top: 20px;">
                <h3 style="color: #1e40af; font-size: 12pt;">Resumen Estadístico</h3>
                <table style="width: 100%; font-size: 9pt;">
                    <tr>
                        <td style="width: 50%; padding: 8px; background-color: #f3f4f6;">
                            <strong>Total de Estudiantes:</strong>
                        </td>
                        <td style="width: 50%; padding: 8px;">
                            {{ count($students) }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 8px; background-color: #f3f4f6;">
                            <strong>Total de Sesiones:</strong>
                        </td>
                        <td style="padding: 8px;">
                            {{ count($dates) }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 8px; background-color: #f3f4f6;">
                            <strong>Promedio General de Asistencia:</strong>
                        </td>
                        <td style="padding: 8px;">
                            {{ number_format($averagePercentage, 2) }}%
                        </td>
                    </tr>
                </table>
            </div>
        @else
            <p style="text-align: center; color: #666; font-style: italic; margin: 30px 0;">
                No hay registros de asistencia para este curso en el periodo seleccionado.
            </p>
        @endif

        <p style="text-align: right; margin-top: 30px;">
            {{ $generatedDate }}
        </p>
    </div>

    <div class="signature-section">
        <div class="signature-line"></div>
        <p class="signature-name">Docente del Curso</p>
        <p class="signature-title">{{ $course->name ?? 'N/A' }}</p>
    </div>
@endsection