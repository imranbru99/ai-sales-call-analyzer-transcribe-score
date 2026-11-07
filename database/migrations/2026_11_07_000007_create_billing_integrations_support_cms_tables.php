<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 5.17 Billing, Subscriptions & Plans
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price_monthly', 10, 2)->default(0.00);
            $table->decimal('price_yearly', 10, 2)->default(0.00);
            $table->string('currency')->default('USD');
            $table->integer('max_agents')->default(5);
            $table->integer('max_minutes_per_month')->default(1000);
            $table->json('included_modules')->nullable(); // array of module keys
            $table->json('features')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('invoice_number')->unique();
            $table->decimal('amount', 10, 2);
            $table->decimal('tax', 10, 2)->default(0.00);
            $table->decimal('total', 10, 2);
            $table->string('currency')->default('USD');
            $table->string('status')->default('paid'); // unpaid, paid, void, refunded
            $table->string('gateway')->default('stripe'); // stripe, paypal, paddle, sslcommerz, bkash, nagad, manual
            $table->timestamp('paid_at')->nullable();
            $table->string('pdf_path')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'status']);
        });

        // 5.19 Integrations Hub & Automation
        Schema::create('integrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('provider'); // twilio, zoom, google_meet, teams, aircall, ringcentral, hubspot, salesforce, slack, webhook
            $table->boolean('is_enabled')->default(true);
            $table->json('credentials')->nullable();
            $table->json('settings')->nullable();
            $table->string('health_status')->default('connected'); // connected, error, unconfigured
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamps();

            $table->unique(['tenant_id', 'provider']);
        });

        Schema::create('workflow_automations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('name');
            $table->string('trigger_event'); // call_scored_low, buying_signal_detected, compliance_violation, call_transcribed
            $table->json('conditions')->nullable(); // e.g. score < 60
            $table->json('actions'); // create_coaching_task, notify_manager, create_deal, send_slack
            $table->boolean('is_active')->default(true);
            $table->integer('execution_count')->default(0);
            $table->timestamps();

            $table->index(['tenant_id', 'is_active']);
        });

        // 5.20 Support, Feedback & Surveys
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('subject');
            $table->string('priority')->default('medium'); // low, medium, high, urgent
            $table->string('status')->default('open'); // open, in_progress, resolved, closed
            $table->string('department')->default('technical');
            $table->timestamps();

            $table->index(['tenant_id', 'status']);
        });

        Schema::create('ticket_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('support_ticket_id')->constrained('support_tickets')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('message');
            $table->boolean('is_staff_reply')->default(false);
            $table->timestamps();
        });

        Schema::create('csat_surveys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('call_id')->constrained('calls')->cascadeOnDelete();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->unsignedBigInteger('contact_id')->nullable();
            $table->integer('score')->default(5); // 1-5
            $table->text('feedback')->nullable();
            $table->string('channel')->default('email'); // sms, email
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'score']);
        });

        // 5.22 Website/CMS Module
        Schema::create('cms_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->boolean('is_published')->default(true);
            $table->string('locale')->default('en');
            $table->timestamps();
        });

        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('featured_image')->nullable();
            $table->string('author_name')->default('Imran Ahmed');
            $table->string('category')->default('Sales Intelligence');
            $table->json('tags')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->string('locale')->default('en');
            $table->timestamps();

            $table->index(['is_published', 'locale']);
        });

        Schema::create('case_studies', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('slug')->unique();
            $table->string('industry');
            $table->string('metric_highlight'); // e.g. "+34% Win Rate in 60 Days"
            $table->text('story');
            $table->string('logo_url')->nullable();
            $table->boolean('is_featured')->default(true);
            $table->timestamps();
        });

        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('author_name');
            $table->string('author_title');
            $table->string('company');
            $table->text('quote');
            $table->integer('rating')->default(5);
            $table->string('avatar_url')->nullable();
            $table->boolean('is_featured')->default(true);
            $table->timestamps();
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->text('answer');
            $table->string('category')->default('General');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();

            $table->index(['is_published', 'category']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('testimonials');
        Schema::dropIfExists('case_studies');
        Schema::dropIfExists('blog_posts');
        Schema::dropIfExists('cms_pages');
        Schema::dropIfExists('csat_surveys');
        Schema::dropIfExists('ticket_replies');
        Schema::dropIfExists('support_tickets');
        Schema::dropIfExists('workflow_automations');
        Schema::dropIfExists('integrations');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('plans');
    }
};
