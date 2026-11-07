<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 5.14 AI Assistant ("Ask Your Calls")
        Schema::create('ai_conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('model')->default('gpt-4o');
            $table->integer('tokens_used')->default(0);
            $table->timestamps();

            $table->index(['tenant_id', 'user_id']);
        });

        Schema::create('ai_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ai_conversation_id')->constrained('ai_conversations')->cascadeOnDelete();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('role')->default('user'); // user, assistant
            $table->text('content');
            $table->json('citations')->nullable(); // call ids, timestamps, quotes
            $table->timestamps();
        });

        // 5.15 Team, HR & Shifts (complements laravel-attendance-hub)
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->string('name'); // Morning Shift, Night Shift, General
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('grace_period_minutes')->default(15);
            $table->timestamps();
        });

        Schema::create('shift_rosters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('shift_id')->constrained('shifts')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->date('date');
            $table->string('status')->default('scheduled'); // scheduled, present, late, absent, leave
            $table->timestamps();

            $table->index(['tenant_id', 'user_id', 'date']);
        });

        // 5.16 Commission, Payroll & Incentives
        Schema::create('commission_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('name');
            $table->string('plan_type')->default('score_bonus'); // flat, tiered, score_bonus, accelerator
            $table->json('rules'); // thresholds, percentages, score accelerators
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('commission_payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->date('period_start');
            $table->date('period_end');
            $table->integer('closed_deals_count')->default(0);
            $table->decimal('avg_call_score', 5, 2)->default(0.00);
            $table->decimal('bonus_amount', 10, 2)->default(0.00);
            $table->decimal('total_payout', 10, 2)->default(0.00);
            $table->string('status')->default('approved'); // pending, approved, paid
            $table->timestamps();

            $table->index(['tenant_id', 'user_id']);
        });

        Schema::create('payroll_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->integer('month');
            $table->integer('year');
            $table->decimal('base_salary', 10, 2)->default(0.00);
            $table->decimal('commission_amount', 10, 2)->default(0.00);
            $table->decimal('deductions', 10, 2)->default(0.00);
            $table->decimal('net_salary', 10, 2)->default(0.00);
            $table->string('payment_status')->default('paid'); // draft, processed, paid
            $table->string('payslip_pdf_path')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'user_id', 'year', 'month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_records');
        Schema::dropIfExists('commission_payouts');
        Schema::dropIfExists('commission_plans');
        Schema::dropIfExists('shift_rosters');
        Schema::dropIfExists('shifts');
        Schema::dropIfExists('ai_messages');
        Schema::dropIfExists('ai_conversations');
    }
};
