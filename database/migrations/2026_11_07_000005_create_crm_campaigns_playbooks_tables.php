<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 5.10 CRM & Leads
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->foreignId('assigned_agent_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('company_name')->nullable();
            $table->string('job_title')->nullable();
            $table->string('status')->default('lead'); // lead, prospect, customer, churned
            $table->integer('lead_score')->default(50);
            $table->json('enrichment_data')->nullable(); // company info scraped via laravel-ai-catalog
            $table->json('custom_fields')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'phone']);
            $table->index(['tenant_id', 'email']);
        });

        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->foreignId('contact_id')->nullable()->constrained('contacts')->nullOnDelete();
            $table->foreignId('agent_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->decimal('amount', 12, 2)->default(0.00);
            $table->string('currency')->default('USD');
            $table->string('pipeline')->default('Sales Pipeline');
            $table->string('stage')->default('discovery'); // discovery, proposal, negotiation, closed_won, closed_lost
            $table->date('close_date')->nullable();
            $table->integer('probability')->default(50); // %
            $table->text('ai_summary')->nullable();
            $table->string('status')->default('open'); // open, won, lost
            $table->timestamps();

            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'stage']);
        });

        Schema::create('deal_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deal_id')->constrained('deals')->cascadeOnDelete();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('call_id')->nullable()->constrained('calls')->nullOnDelete();
            $table->string('type')->default('call'); // call, meeting, note, email, task
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 5.11 Campaigns & Dialer
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->string('name');
            $table->string('dialer_mode')->default('preview'); // preview, progressive, power
            $table->string('status')->default('active'); // draft, active, paused, completed
            $table->string('caller_id')->nullable();
            $table->integer('total_leads')->default(0);
            $table->integer('completed_leads')->default(0);
            $table->decimal('connection_rate', 5, 2)->default(0.00);
            $table->decimal('conversion_rate', 5, 2)->default(0.00);
            $table->json('settings')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'status']);
        });

        Schema::create('campaign_leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('campaigns')->cascadeOnDelete();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('contact_id')->constrained('contacts')->cascadeOnDelete();
            $table->foreignId('assigned_agent_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('call_id')->nullable()->constrained('calls')->nullOnDelete();
            $table->string('status')->default('pending'); // pending, dialing, connected, callback, completed, dnc
            $table->integer('attempts')->default(0);
            $table->dateTime('last_called_at')->nullable();
            $table->dateTime('next_callback_at')->nullable();
            $table->string('outcome')->nullable();
            $table->timestamps();

            $table->index(['campaign_id', 'status']);
        });

        // 5.12 Scripts, Playbooks & Battlecards
        Schema::create('scripts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug');
            $table->string('call_type')->default('cold_call');
            $table->string('version')->default('1.0');
            $table->text('content');
            $table->json('objection_flow')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['tenant_id', 'is_active']);
        });

        Schema::create('battlecards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('competitor_name');
            $table->string('slug');
            $table->text('competitor_strengths')->nullable();
            $table->text('our_advantages')->nullable();
            $table->json('counter_points')->nullable(); // objection handling points
            $table->text('pricing_intel')->nullable();
            $table->timestamp('auto_researched_at')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'competitor_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('battlecards');
        Schema::dropIfExists('scripts');
        Schema::dropIfExists('campaign_leads');
        Schema::dropIfExists('campaigns');
        Schema::dropIfExists('deal_activities');
        Schema::dropIfExists('deals');
        Schema::dropIfExists('contacts');
    }
};
