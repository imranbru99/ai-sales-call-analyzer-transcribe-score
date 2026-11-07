<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scorecards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('name');
            $table->string('call_type')->default('sales_demo'); // cold_call, sales_demo, follow_up, support, collections
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->decimal('pass_threshold', 5, 2)->default(70.00);
            $table->json('criteria'); // sections, criteria weights, questions, critical_fail flags
            $table->timestamps();

            $table->index(['tenant_id', 'call_type']);
            $table->index(['tenant_id', 'is_active']);
        });

        Schema::create('calls', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->foreignId('team_id')->nullable()->constrained('teams')->nullOnDelete();
            $table->foreignId('agent_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('deal_id')->nullable();
            $table->unsignedBigInteger('campaign_id')->nullable();
            $table->foreignId('scorecard_id')->nullable()->constrained('scorecards')->nullOnDelete();

            $table->string('title');
            $table->string('call_type')->default('outbound'); // inbound, outbound, internal, web_meeting
            $table->string('direction')->default('outbound'); // inbound, outbound
            $table->string('source')->default('upload'); // upload, twilio, zoom, google_meet, teams, aircall, ringcentral, asterisk, webrtc, api
            $table->string('caller_number')->nullable();
            $table->string('callee_number')->nullable();
            $table->string('caller_name')->nullable();
            $table->string('callee_name')->nullable();
            $table->string('status')->default('pending'); // pending, processing, transcribed, analyzed, scored, failed
            $table->integer('duration_seconds')->default(0);

            $table->string('audio_url')->nullable();
            $table->string('audio_path')->nullable();
            $table->string('audio_disk')->default('local');
            $table->string('audio_format')->default('mp3');
            $table->unsignedBigInteger('audio_size_bytes')->nullable();
            $table->json('waveform_data')->nullable();
            $table->boolean('consent_recorded')->default(true);
            $table->string('jurisdiction')->nullable();
            $table->string('external_call_id')->nullable();
            $table->string('language')->default('en');

            // Scoring & Metrics
            $table->decimal('overall_score', 5, 2)->nullable();
            $table->string('sentiment_label')->default('neutral'); // positive, neutral, negative, mixed
            $table->decimal('sentiment_score', 5, 2)->default(0.00); // -1.0 to 1.0 or 0-100
            $table->string('outcome')->nullable(); // interested, not_interested, callback, closed_won, closed_lost, wrong_number
            $table->integer('deal_likelihood_score')->nullable(); // 0-100
            $table->integer('talk_time_agent_sec')->default(0);
            $table->integer('talk_time_customer_sec')->default(0);
            $table->integer('silence_time_sec')->default(0);
            $table->decimal('talk_listen_ratio', 5, 2)->default(0.00); // e.g. 1.2
            $table->integer('words_per_minute')->default(0);

            // Rich Analysis Insights
            $table->text('summary')->nullable();
            $table->text('executive_summary')->nullable();
            $table->json('action_items')->nullable();
            $table->json('key_moments')->nullable();
            $table->json('topics')->nullable();
            $table->json('buying_signals')->nullable();
            $table->json('churn_risks')->nullable();
            $table->json('objections')->nullable();
            $table->json('questions_asked')->nullable();
            $table->json('competitor_mentions')->nullable();
            $table->json('pricing_mentions')->nullable();
            $table->json('methodology_scores')->nullable(); // BANT, MEDDIC, SPIN, Challenger
            $table->decimal('script_adherence_score', 5, 2)->nullable();
            $table->json('scorecard_breakdown')->nullable();

            $table->string('ai_provider_used')->nullable();
            $table->decimal('ai_cost', 8, 4)->default(0.0000);
            $table->boolean('pii_redacted')->default(false);
            $table->timestamp('retention_until')->nullable();
            $table->boolean('is_starred')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'agent_id']);
            $table->index(['tenant_id', 'overall_score']);
            $table->index(['tenant_id', 'created_at']);
            $table->index(['tenant_id', 'branch_id']);
        });

        Schema::create('transcript_segments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('call_id')->constrained('calls')->cascadeOnDelete();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('speaker')->default('agent'); // agent, customer, other
            $table->string('speaker_name')->nullable();
            $table->decimal('start_time', 8, 2);
            $table->decimal('end_time', 8, 2);
            $table->text('text');
            $table->string('sentiment')->default('neutral');
            $table->decimal('sentiment_score', 4, 2)->default(0.00);
            $table->decimal('confidence', 4, 2)->default(0.95);
            $table->json('words_data')->nullable(); // word timestamps
            $table->json('pii_entities')->nullable();
            $table->boolean('is_redacted')->default(false);
            $table->timestamps();

            $table->index(['call_id', 'start_time']);
            $table->index(['tenant_id', 'speaker']);
        });

        Schema::create('call_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('call_id')->constrained('calls')->cascadeOnDelete();
            $table->foreignId('scorecard_id')->constrained('scorecards')->cascadeOnDelete();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('scored_by')->default('ai'); // ai, qa, manager
            $table->unsignedBigInteger('evaluator_id')->nullable();
            $table->decimal('total_score', 5, 2);
            $table->boolean('passed')->default(true);
            $table->boolean('has_critical_failure')->default(false);
            $table->json('answers'); // criterion evaluations, feedback, quote, timestamps
            $table->string('dispute_status')->default('none'); // none, disputed, resolved, upheld, overturned
            $table->text('dispute_comment')->nullable();
            $table->timestamps();

            $table->index(['call_id', 'scorecard_id']);
            $table->index(['tenant_id', 'scored_by']);
        });

        Schema::create('call_annotations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('call_id')->constrained('calls')->cascadeOnDelete();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('timestamp_sec', 8, 2)->nullable();
            $table->text('content');
            $table->string('category')->default('comment'); // comment, coaching_note, qa_flag, praise, correction
            $table->timestamps();

            $table->index(['call_id', 'timestamp_sec']);
            $table->index(['tenant_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('call_annotations');
        Schema::dropIfExists('call_scores');
        Schema::dropIfExists('transcript_segments');
        Schema::dropIfExists('calls');
        Schema::dropIfExists('scorecards');
    }
};
