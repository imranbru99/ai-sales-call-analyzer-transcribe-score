<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 5.7 Coaching & Training
        Schema::create('coaching_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('agent_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('coach_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->string('status')->default('active'); // active, completed, paused
            $table->text('strengths')->nullable();
            $table->text('gaps')->nullable();
            $table->json('focus_areas')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'agent_id']);
        });

        Schema::create('coaching_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coaching_plan_id')->constrained('coaching_plans')->cascadeOnDelete();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('agent_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('call_id')->nullable()->constrained('calls')->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('due_date')->nullable();
            $table->string('status')->default('pending'); // pending, in_progress, completed
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'agent_id', 'status']);
        });

        Schema::create('training_courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('content_type')->default('video'); // video, audio, document, quiz
            $table->integer('duration_minutes')->default(30);
            $table->string('category')->default('sales_techniques');
            $table->boolean('is_published')->default(true);
            $table->timestamps();

            $table->index(['tenant_id', 'is_published']);
        });

        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_course_id')->constrained('training_courses')->cascadeOnDelete();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('title');
            $table->json('questions'); // question text, choices, correct_index, explanation
            $table->integer('passing_score')->default(80);
            $table->timestamps();
        });

        Schema::create('roleplay_scenarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('title');
            $table->string('persona_name');
            $table->string('persona_industry');
            $table->string('objection_type'); // pricing, competitor, need, timing
            $table->text('script_prompt');
            $table->string('difficulty')->default('intermediate'); // easy, intermediate, hard
            $table->timestamps();
        });

        // 5.8 Quality Assurance (QA) Workflow
        Schema::create('calibration_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('call_id')->constrained('calls')->cascadeOnDelete();
            $table->string('title');
            $table->dateTime('scheduled_at')->nullable();
            $table->string('status')->default('completed'); // scheduled, in_progress, completed
            $table->decimal('variance_score', 5, 2)->default(0.00);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('qa_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('call_id')->constrained('calls')->cascadeOnDelete();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('analyst_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('calibration_session_id')->nullable()->constrained('calibration_sessions')->nullOnDelete();
            $table->string('status')->default('completed'); // pending, in_progress, completed, disputed
            $table->decimal('score', 5, 2)->default(0.00);
            $table->text('notes')->nullable();
            $table->text('feedback_to_agent')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'analyst_id']);
        });

        // 5.9 Compliance & Risk
        Schema::create('compliance_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('name');
            $table->string('rule_type'); // required_phrase, forbidden_phrase, mandatory_disclosure, regulated_claim, recording_consent
            $table->json('phrases'); // array of string patterns
            $table->string('severity')->default('medium'); // low, medium, high, critical
            $table->string('industry_pack')->nullable(); // finance, insurance, healthcare, debt_collection, real_estate
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['tenant_id', 'is_active']);
            $table->index(['tenant_id', 'rule_type']);
        });

        Schema::create('compliance_violations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('call_id')->constrained('calls')->cascadeOnDelete();
            $table->foreignId('compliance_rule_id')->constrained('compliance_rules')->cascadeOnDelete();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('agent_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('severity')->default('medium');
            $table->text('quote');
            $table->decimal('timestamp_sec', 8, 2)->nullable();
            $table->string('remediation_status')->default('flagged'); // flagged, reviewed, remediated, false_positive
            $table->text('remediation_notes')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'severity']);
            $table->index(['tenant_id', 'remediation_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compliance_violations');
        Schema::dropIfExists('compliance_rules');
        Schema::dropIfExists('qa_reviews');
        Schema::dropIfExists('calibration_sessions');
        Schema::dropIfExists('roleplay_scenarios');
        Schema::dropIfExists('quizzes');
        Schema::dropIfExists('training_courses');
        Schema::dropIfExists('coaching_actions');
        Schema::dropIfExists('coaching_plans');
    }
};
