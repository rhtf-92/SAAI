@extends('emails.layout')

@section('title', 'Pago Recibido - SAAI')

@section('content')
    <h2>¡Pago Recibido!</h2>

    <p>Hola,</p>

    <p>Te confirmamos que hemos recibido tu pago exitosamente.</p>

    <div style="background-color: #f3f4f6; padding: 20px; border-radius: 6px; margin: 20px 0;">
        <h3 style="margin-top: 0; color: #374151;">Detalles del Pago</h3>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="padding: 8px 0; color: #6b7280;"><strong>Monto:</strong></td>
                <td style="padding: 8px 0; text-align: right;">S/ {{ number_format($payment->amount, 2) }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; color: #6b7280;"><strong>Método de Pago:</strong></td>
                <td style="padding: 8px 0; text-align: right;">{{ $payment->payment_method }}</td>
            </tr>
            @if($payment->operation_number)
                <tr>
                    <td style="padding: 8px 0; color: #6b7280;"><strong>N° Operación:</strong></td>
                    <td style="padding: 8px 0; text-align: right;">{{ $payment->operation_number }}</td>
                </tr>
            @endif
            <tr>
                <td style="padding: 8px 0; color: #6b7280;"><strong>Fecha:</strong></td>
                <td style="padding: 8px 0; text-align: right;">{{ $payment->paid_at->format('d/m/Y H:i') }}</td>
            </tr>
        </table>
    </div>

    <p>Este pago ha sido registrado en tu cuenta y será reflejado en tu estado de cuenta.</p>

    <a href="{{ config('app.url') }}/payments" class="email-button">Ver Mis Pagos</a>

    <p style="margin-top: 30px; color: #6b7280; font-size: 14px;">
        Gracias por tu pago puntual.
    </p>
@endsection