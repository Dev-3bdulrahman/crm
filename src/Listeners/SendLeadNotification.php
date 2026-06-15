<?php

namespace Dev3bdulrahman\Crm\Listeners;

use Dev3bdulrahman\Crm\Events\LeadCreated;
use Dev3bdulrahman\Crm\Notifications\NewLeadAssignedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendLeadNotification implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Handle the LeadCreated event.
     *
     * Sends a database notification to the assigned user when a new lead is created.
     * Skips silently if no user is assigned.
     */
    public function handle(LeadCreated $event): void
    {
        $lead = $event->lead;

        if (is_null($lead->assigned_to)) {
            return;
        }

        $assignee = $lead->assignee;

        if (is_null($assignee)) {
            return;
        }

        $assignee->notify(new NewLeadAssignedNotification($lead));
    }
}
