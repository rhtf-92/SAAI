<?php

namespace App\Listeners;

use App\Events\PaymentReceived;
use App\Services\NotificationService;

class SendPaymentNotification
{
    protected $notificationService;

    /**
     * Create the event listener.
     */
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle the event.
     */
    public function handle(PaymentReceived $event): void
    {
        $payment = $event->payment;
        $student = $event->student;

        // Create notification in database
        $this->notificationService->create(
            $student->user_id,
            'payment_received',
            'Pago Recibido',
            sprintf(
                'Tu pago de S/ %.2f ha sido procesado exitosamente.',
                $payment->amount
            ),
            [
                'payment_id' => $payment->id,
                'amount' => $payment->amount,
                'payment_method' => $payment->payment_method,
                'operation_number' => $payment->operation_number,
            ]
        );

        // TODO: Send email notification
    }
}
