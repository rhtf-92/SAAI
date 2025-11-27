@extends('emails.layout')

@section('title', 'Confirmación de Matrícula - SAAI')

@section('content')
    <h2>¡Matrícula Confirmada!</h2>

    <p>Hola {{ $student->user->name }},</p>

    <p>Nos complace informarte que tu matrícula ha sido confirmada exitosamente.</p>

    <div style="background-color: #f3f4f6; padding: 20px; border-radius: 6px; margin: 20px 0;">
        <h3 style="margin-top: 0; color: #374151;">Detalles de la Matrícula</h3>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="padding: 8px 0; color: #6b7280;"><strong>Periodo:</strong></td>
                <td style="padding: 8px 0; text-align: right;">{{ $enrollment->term->name ?? 'Periodo Actual' }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; color: #6b7280;"><strong>Plan de Estudios:</strong></td>
                <td style="padding: 8px 0; text-align: right;">{{ $enrollment->plan->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; color: #6b7280;"><strong>Fecha de Matrícula:</strong></td>
                <td style="padding: 8px 0; text-align: right;">
                    {{ $enrollment->date ? $enrollment->date->format('d/m/Y') : date('d/m/Y') }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; color: #6b7280;"><strong>Estado:</strong></td>
                <td style="padding: 8px 0; text-align: right;">
                    <span
                        style="background-color: #10b981; color: white; padding: 4px 12px; border-radius: 4px; font-size: 12px;">
                        {{ ucfirst($enrollment->status) }}
                    </span>
                </td>
            </tr>
        </table>
    </div>

    <p>Ya puedes acceder a tu horario de clases y comenzar tus estudios.</p>

    <a href="{{ config('app.url') }}/enrollments" class="email-button">Ver Mi Matrícula</a>

    <p style="margin-top: 30px; color: #6b7280; font-size: 14px;">
        ¡Te deseamos mucho éxito en este nuevo periodo académico!
    </p>
@endsection