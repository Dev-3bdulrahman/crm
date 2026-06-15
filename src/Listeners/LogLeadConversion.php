<?php

namespace Dev3bdulrahman\Crm\Listeners;

use App\Services\AuditLogService;
use Dev3bdulrahman\Crm\Events\LeadConverted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class LogLeadConversion implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private AuditLogService $auditLogService,
    ) {}

    /**
     * Handle the LeadConverted event.
     *
     * Creates an AuditLog entry recording the lead conversion details.
     */
    public function handle(LeadConverted $event): void
    {
        try {
            $this->auditLogService->log(
                action: 'lead_converted',
                companyId: $event->lead->company_id,
                userId: $event->userId,
                model: $event->lead,
                oldValues: null,
                newValues: [
                    'lead_id' => $event->lead->id,
                    'conversion_type' => $event->conversionType,
                    'customer_id' => $event->customer?->id,
                    'opportunity_id' => $event->opportunity?->id,
                ],
            );
        } catch (\Throwable $e) {
            Log::error('LogLeadConversion: Failed to log lead conversion.', [
                'error' => $e->getMessage(),
                'lead_id' => $event->lead->id ?? null,
                'user_id' => $event->userId ?? null,
            ]);
        }
    }
}
