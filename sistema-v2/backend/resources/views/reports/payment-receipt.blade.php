@extends('reports.layout')

@section('title', 'Comprobante de Pago')

@section('content')
    <div class="document-number">
        N° {{ str_pad($payment->id, 8, '0', STR_PAD_LEFT) }}
    </div>

    <div class="document-title">
        <h2>COMPROBANTE DE PAGO</h2>
    </div>

    <div class="content">
        <div class="info-box">
            <p><strong>Estudiante:</strong> {{ $student->user->name ?? 'N/A' }}</p>
            <p><strong>Documento de Identidad:</strong> {{ $student->user->document_type ?? 'DNI' }} -
                {{ $student->user->document_number ?? 'N/A' }}</p>
            <p><strong>Código de Estudiante:</strong> {{ str_pad($student->id, 8, '0', STR_PAD_LEFT) }}</p>
        </div>

        <p style="margin: 20px 0; font-size: 12pt;">
            Se ha registrado exitosamente el siguiente pago:
        </p>

        <table>
            <tr>
                <td style="width: 35%; background-color: #f3f4f6; font-weight: bold;">N° de Operación:</td>
                <td style="width: 65%;">{{ $payment->operation_number ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="background-color: #f3f4f6; font-weight: bold;">Concepto de Pago:</td>
                <td>{{ $concept->name ?? 'Pago General' }}</td>
            </tr>
            <tr>
                <td style="background-color: #f3f4f6; font-weight: bold;">Método de Pago:</td>
                <td>{{ ucfirst($payment->payment_method) }}</td>
            </tr>
            <tr>
                <td style="background-color: #f3f4f6; font-weight: bold;">Banco:</td>
                <td>{{ $payment->bank ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="background-color: #f3f4f6; font-weight: bold;">Fecha y Hora:</td>
                <td>{{ $payment->paid_at ? $payment->paid_at->format('d/m/Y H:i') : 'N/A' }}</td>
            </tr>
            <tr style="background-color: #dbeafe;">
                <td style="font-weight: bold; font-size: 14pt; color: #1e40af;">MONTO TOTAL:</td>
                <td style="font-weight: bold; font-size: 14pt; color: #1e40af;">S/ {{ number_format($payment->amount, 2) }}
                </td>
            </tr>
            <tr>
                <td style="background-color: #f3f4f6; font-weight: bold;">Estado:</td>
                <td>
                    @if($payment->status === 'paid')
                        <strong style="color: #10b981;">✓ PAGADO</strong>
                    @elseif($payment->status === 'pending')
                        <strong style="color: #f59e0b;">⏱ PENDIENTE</strong>
                    @elseif($payment->status === 'cancelled')
                        <strong style="color: #ef4444;">✗ CANCELADO</strong>
                    @else
                        <strong>{{ strtoupper($payment->status) }}</strong>
                    @endif
                </td>
            </tr>
        </table>

        @if($payment->notes)
            <div class="info-box" style="margin-top: 20px;">
                <p><strong>Observaciones:</strong></p>
                <p>{{ $payment->notes }}</p>
            </div>
        @endif

        <p style="margin-top: 30px; text-align: center; font-size: 10pt; color: #666;">
            Este comprobante es un documento válido que certifica el pago realizado.<br>
            Conserve este documento para futuras consultas o reclamos.
        </p>

        <p style="text-align: center; margin-top: 20px; font-size: 9pt; color: #999;">
            Código de Verificación: {{ strtoupper(substr(md5($payment->id . $payment->created_at), 0, 12)) }}
        </p>

        <p style="text-align: right; margin-top: 30px;">
            Emitido el: {{ $date }}
        </p>
    </div>

    <div class="signature-section">
        <p style="font-size: 9pt; color: #666; margin-top: 40px;">
            Este documento ha sido generado electrónicamente y no requiere firma.
        </p>
    </div>
@endsection