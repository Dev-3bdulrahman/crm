<?php

namespace Dev3bdulrahman\Crm\Events;

use Dev3bdulrahman\Crm\Models\Customer;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CustomerCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Customer $customer,
        public string $source, // 'direct' or 'converted_from_lead'
        public int $companyId,
    ) {}
}
