<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('domain')->nullable()->unique();
            $table->string('business_type')->default('call_center'); // call_center, real_estate, saas, insurance, solo_freelancer
            $table->string('status')->default('active'); // active, suspended, trial
            $table->unsignedBigInteger('plan_id')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->json('settings')->nullable(); // currency, timezone, language, etc.
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('business_type');
        });

        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('name');
            $table->string('code')->default('MAIN');
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('timezone')->default('UTC');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->boolean('is_head_office')->default(false);
            $table->string('status')->default('active');
            $table->timestamps();

            $table->index(['tenant_id', 'status']);
        });

        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'branch_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('tenant_id')->nullable()->after('id')->constrained('tenants')->nullOnDelete();
            $table->foreignId('branch_id')->nullable()->after('tenant_id')->constrained('branches')->nullOnDelete();
            $table->foreignId('team_id')->nullable()->after('branch_id')->constrained('teams')->nullOnDelete();
            $table->string('phone')->nullable()->after('email');
            $table->string('role')->default('agent')->after('phone');
            // super_admin, tenant_owner, branch_manager, sales_manager, agent, qa_analyst, trainer, compliance_officer, finance, hr_officer, customer, reseller, api_user
            $table->string('locale')->default('en')->after('role');
            $table->string('timezone')->default('UTC')->after('locale');
            $table->string('avatar_url')->nullable()->after('timezone');
            $table->boolean('is_active')->default(true)->after('avatar_url');
            $table->boolean('two_factor_enabled')->default(false)->after('is_active');
            $table->string('two_factor_secret')->nullable()->after('two_factor_enabled');
            $table->json('preferences')->nullable()->after('two_factor_secret');

            $table->index(['tenant_id', 'role']);
            $table->index(['tenant_id', 'branch_id']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropForeign(['branch_id']);
            $table->dropForeign(['team_id']);
            $table->dropColumn([
                'tenant_id', 'branch_id', 'team_id', 'phone', 'role',
                'locale', 'timezone', 'avatar_url', 'is_active',
                'two_factor_enabled', 'two_factor_secret', 'preferences',
            ]);
        });

        Schema::dropIfExists('teams');
        Schema::dropIfExists('branches');
        Schema::dropIfExists('tenants');
    }
};
