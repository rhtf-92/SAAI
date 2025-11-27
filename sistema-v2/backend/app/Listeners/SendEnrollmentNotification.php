<?php

namespace App\Listeners;

use App\Events\StudentEnrolled;
use App\Services\NotificationService;

class SendEnrollmentNotification
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
    public function handle(StudentEnrolled $event): void
    {
        $enrollment = $event->enrollment;
        $student = $event->student;

        // Create notification in database
        $this->notificationService->create(
            $student->user_id,
            'student_enrolled',
            'Matrícula Confirmada',
            sprintf(
                'Tu matrícula para el periodo %s ha sido confirmada exitosamente.',
                $enrollment->term->name ?? 'actual'
            ),
            [
                'enrollment_id' => $enrollment->id,
                'term_id' => $enrollment->term_id,
                'plan_id' => $enrollment->plan_id,
            ]
        );

        // TODO: Send email notification
    }
}
