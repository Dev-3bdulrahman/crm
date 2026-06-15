<?php

namespace Dev3bdulrahman\Crm\Events;

use Dev3bdulrahman\Crm\Models\Customer;
use Dev3bdulrahman\Crm\Models\Lead;
use Dev3bdulrahman\Crm\Models\Opportunity;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LeadConverted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Lead $lead,
        public ?Customer $customer,
        public ?Opportunity $opportunity,
        public string $conversionType, // 'to_customer', 'to_opportunity', 'both'
        public int $userId,
    ) {}
}
