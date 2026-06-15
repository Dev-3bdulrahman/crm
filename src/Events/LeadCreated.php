<?php

namespace Dev3bdulrahman\Crm\Events;

use Dev3bdulrahman\Crm\Models\Lead;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LeadCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Lead $lead,
        public int $userId,
        public int $companyId,
    ) {}

    public function broadcastOn(): array
    {
        return [new PrivateChannel("company.{$this->companyId}")];
    }
}
