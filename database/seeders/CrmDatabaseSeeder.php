<?php

namespace Dev3bdulrahman\Crm\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\Company;
use Dev3bdulrahman\Crm\Models\LeadSource;
use Dev3bdulrahman\Crm\Models\LeadStatus;
use Dev3bdulrahman\Crm\Models\Pipeline;
use Dev3bdulrahman\Crm\Models\PipelineStage;

class CrmDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed CRM-specific Spatie permissions
        $permissions = [
            'crm.leads.view',
            'crm.leads.create',
            'crm.leads.edit',
            'crm.leads.delete',
            'crm.leads.convert',

            'crm.customers.view',
            'crm.customers.create',
            'crm.customers.edit',
            'crm.customers.delete',

            'crm.opportunities.view',
            'crm.opportunities.create',
            'crm.opportunities.edit',
            'crm.opportunities.delete',

            'crm.activities.view',
            'crm.activities.create',
            'crm.activities.edit',
            'crm.activities.delete',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
        }

        // Sync to roles
        $superAdmin = Role::where('name', 'super-admin')->first();
        if ($superAdmin) {
            $superAdmin->givePermissionTo($permissions);
        }

        $admin = Role::where('name', 'admin')->first();
        if ($admin) {
            $admin->givePermissionTo($permissions);
        }

        // 2. Seed default values for each company
        $companies = Company::all();

        foreach ($companies as $company) {
            // Seed Lead Sources
            $sources = ['الموقع الإلكتروني', 'مكالمة هاتفية', 'وسائل التواصل الاجتماعي', 'إعلان مدفوع', 'توصية'];
            foreach ($sources as $sourceName) {
                LeadSource::firstOrCreate([
                    'company_id' => $company->id,
                    'name' => $sourceName,
                ], [
                    'status' => 'active'
                ]);
            }

            // Seed Lead Statuses
            $statuses = [
                ['name' => 'جديد', 'color' => 'blue', 'sort_order' => 1, 'is_converted' => false],
                ['name' => 'تم التواصل', 'color' => 'yellow', 'sort_order' => 2, 'is_converted' => false],
                ['name' => 'مؤهل', 'color' => 'green', 'sort_order' => 3, 'is_converted' => false],
                ['name' => 'غير مؤهل', 'color' => 'red', 'sort_order' => 4, 'is_converted' => false],
                ['name' => 'تم التحويل', 'color' => 'purple', 'sort_order' => 5, 'is_converted' => true],
            ];
            foreach ($statuses as $statusData) {
                LeadStatus::firstOrCreate([
                    'company_id' => $company->id,
                    'name' => $statusData['name'],
                ], [
                    'color' => $statusData['color'],
                    'sort_order' => $statusData['sort_order'],
                    'is_converted' => $statusData['is_converted'],
                    'status' => 'active'
                ]);
            }

            // Seed Default Pipeline
            $pipeline = Pipeline::firstOrCreate([
                'company_id' => $company->id,
                'name' => 'خط المبيعات الافتراضي',
            ], [
                'is_default' => true
            ]);

            // Seed Pipeline Stages
            $stages = [
                ['name' => 'عميل محتمل', 'probability' => 10, 'sort_order' => 1],
                ['name' => 'تم التواصل الأولي', 'probability' => 30, 'sort_order' => 2],
                ['name' => 'تحديد موعد اجتماع', 'probability' => 50, 'sort_order' => 3],
                ['name' => 'تقديم العرض المالي', 'probability' => 80, 'sort_order' => 4],
                ['name' => 'مفاوضات نهائية', 'probability' => 90, 'sort_order' => 5],
            ];
            foreach ($stages as $stageData) {
                PipelineStage::firstOrCreate([
                    'company_id' => $company->id,
                    'pipeline_id' => $pipeline->id,
                    'name' => $stageData['name'],
                ], [
                    'probability' => $stageData['probability'],
                    'sort_order' => $stageData['sort_order'],
                ]);
            }
        }
    }
}
