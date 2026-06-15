<?php

namespace Dev3bdulrahman\Crm\Notifications;

use Dev3bdulrahman\Crm\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewLeadAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected Lead $lead,
    ) {}

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification for database storage.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'lead_id' => $this->lead->id,
            'lead_name' => $this->lead->name,
            'lead_title' => $this->lead->title,
            'message' => __('crm::crm.new_lead_assigned', [
                'name' => $this->lead->name,
            ]),
        ];
    }
}
