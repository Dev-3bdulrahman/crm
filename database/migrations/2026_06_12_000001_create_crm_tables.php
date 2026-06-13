<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Lead Sources
        Schema::create('crm_lead_sources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->string('name');
            $table->string('status')->default('active'); // active, inactive
            $table->timestamps();

            $table->index('company_id');
            $table->index('status');
        });

        // 2. Lead Statuses
        Schema::create('crm_lead_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->string('name');
            $table->string('color')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_converted')->default(false);
            $table->string('status')->default('active'); // active, inactive
            $table->timestamps();

            $table->index('company_id');
            $table->index('status');
        });

        // 3. Organizations
        Schema::create('crm_organizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->string('address')->nullable();
            $table->string('status')->default('active'); // active, inactive
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('status');
        });

        // 4. Contacts
        Schema::create('crm_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('organization_id')->nullable()->constrained('crm_organizations')->onDelete('set null');
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('job_title')->nullable();
            $table->string('status')->default('active'); // active, inactive
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('organization_id');
            $table->index('status');
        });

        // 5. Leads
        Schema::create('crm_leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('lead_source_id')->nullable()->constrained('crm_lead_sources')->onDelete('set null');
            $table->foreignId('lead_status_id')->nullable()->constrained('crm_lead_statuses')->onDelete('set null');
            $table->foreignId('contact_id')->nullable()->constrained('crm_contacts')->onDelete('set null');
            $table->string('title');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('company_name')->nullable();
            $table->decimal('value', 15, 2)->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->string('status')->default('active'); // active, inactive, converted, lost
            $table->timestamp('converted_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('lead_source_id');
            $table->index('lead_status_id');
            $table->index('contact_id');
            $table->index('assigned_to');
            $table->index('status');
        });

        // 6. Customer Groups
        Schema::create('crm_customer_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->string('name');
            $table->timestamps();

            $table->index('company_id');
        });

        // 7. Customers
        Schema::create('crm_customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('customer_group_id')->nullable()->constrained('crm_customer_groups')->onDelete('set null');
            $table->foreignId('organization_id')->nullable()->constrained('crm_organizations')->onDelete('set null');
            $table->foreignId('contact_id')->nullable()->constrained('crm_contacts')->onDelete('set null');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('status')->default('active'); // active, inactive
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('customer_group_id');
            $table->index('organization_id');
            $table->index('contact_id');
            $table->index('status');
        });

        // 8. Pipelines
        Schema::create('crm_pipelines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->string('name');
            $table->boolean('is_default')->default(false);
            $table->timestamps();

            $table->index('company_id');
        });

        // 9. Pipeline Stages
        Schema::create('crm_pipeline_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('pipeline_id')->constrained('crm_pipelines')->onDelete('cascade');
            $table->string('name');
            $table->integer('probability')->default(0);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index('company_id');
            $table->index('pipeline_id');
        });

        // 10. Opportunities
        Schema::create('crm_opportunities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('pipeline_id')->constrained('crm_pipelines')->onDelete('cascade');
            $table->foreignId('pipeline_stage_id')->constrained('crm_pipeline_stages')->onDelete('cascade');
            $table->foreignId('customer_id')->nullable()->constrained('crm_customers')->onDelete('set null');
            $table->foreignId('lead_id')->nullable()->constrained('crm_leads')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Owner
            $table->string('name');
            $table->decimal('value', 15, 2)->default(0.00);
            $table->date('close_date')->nullable();
            $table->string('status')->default('open'); // open, won, lost
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('pipeline_id');
            $table->index('pipeline_stage_id');
            $table->index('customer_id');
            $table->index('lead_id');
            $table->index('user_id');
            $table->index('status');
        });

        // 11. Activities
        Schema::create('crm_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Assignee
            $table->string('subject_type');
            $table->unsignedBigInteger('subject_id');
            $table->string('type'); // call, meeting, email, task
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('due_date');
            $table->string('status')->default('pending'); // pending, completed, cancelled
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('user_id');
            $table->index(['subject_type', 'subject_id']);
            $table->index('status');
        });

        // 12. Notes
        Schema::create('crm_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('noteable_type');
            $table->unsignedBigInteger('noteable_id');
            $table->text('content');
            $table->timestamps();

            $table->index('company_id');
            $table->index('user_id');
            $table->index(['noteable_type', 'noteable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_notes');
        Schema::dropIfExists('crm_activities');
        Schema::dropIfExists('crm_opportunities');
        Schema::dropIfExists('crm_pipeline_stages');
        Schema::dropIfExists('crm_pipelines');
        Schema::dropIfExists('crm_customers');
        Schema::dropIfExists('crm_customer_groups');
        Schema::dropIfExists('crm_leads');
        Schema::dropIfExists('crm_contacts');
        Schema::dropIfExists('crm_organizations');
        Schema::dropIfExists('crm_lead_statuses');
        Schema::dropIfExists('crm_lead_sources');
    }
};
