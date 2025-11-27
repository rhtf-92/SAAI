<?php

namespace App\Providers;

use App\Events\PaymentReceived;
use App\Events\StudentEnrolled;
use App\Listeners\SendPaymentNotification;
use App\Listeners\SendEnrollmentNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        PaymentReceived::class => [
            SendPaymentNotification::class,
        ],
        StudentEnrolled::class => [
            SendEnrollmentNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
