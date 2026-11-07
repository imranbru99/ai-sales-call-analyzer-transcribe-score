<?php

namespace Tests\Feature;

use App\Facades\Module;
use App\Models\Branch;
use App\Models\Tenant;
use App\Services\Tenancy\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenancyAndModuleSystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Module::syncCatalog();
    }

    public function test_modules_catalog_synced_and_core_modules_always_enabled(): void
    {
        $this->assertTrue(Module::enabled('call_capture'));
        $this->assertTrue(Module::enabled('transcription'));
        $this->assertTrue(Module::enabled('security_system'));
        $this->assertTrue(Module::enabled('website_cms'));
    }

    public function test_tenant_module_toggle_works_per_tenant(): void
    {
        $tenantA = Tenant::create([
            'name' => 'Tenant Alpha',
            'slug' => 'alpha',
            'business_type' => 'call_center',
        ]);

        $tenantB = Tenant::create([
            'name' => 'Tenant Beta',
            'slug' => 'beta',
            'business_type' => 'solo_freelancer',
        ]);

        // Enable ai_scoring for Tenant A only
        Module::enable('ai_scoring', $tenantA);
        Module::disable('ai_scoring', $tenantB);

        TenantContext::setTenant($tenantA);
        $this->assertTrue(Module::enabled('ai_scoring', $tenantA));

        TenantContext::setTenant($tenantB);
        $this->assertFalse(Module::enabled('ai_scoring', $tenantB));
    }

    public function test_preset_application_applies_correct_modules(): void
    {
        $tenant = Tenant::create([
            'name' => 'Solo Realtor',
            'slug' => 'solo-realtor',
            'business_type' => 'solo_freelancer',
        ]);

        Module::applyPreset('solo_freelancer', $tenant);

        // Core / included
        $this->assertTrue(Module::enabled('call_capture', $tenant));
        $this->assertTrue(Module::enabled('transcription', $tenant));

        // Not included in solo freelancer
        $this->assertFalse(Module::enabled('live_assist', $tenant));
        $this->assertFalse(Module::enabled('qa_workflow', $tenant));
        $this->assertFalse(Module::enabled('attendance_hr', $tenant));
    }

    public function test_tenant_data_isolation(): void
    {
        $tenantA = Tenant::create(['name' => 'Acme Corp', 'slug' => 'acme']);
        $tenantB = Tenant::create(['name' => 'Stark Industries', 'slug' => 'stark']);

        $branchA = Branch::create(['tenant_id' => $tenantA->id, 'name' => 'Acme Branch 1', 'code' => 'ACME-1']);
        $branchB = Branch::create(['tenant_id' => $tenantB->id, 'name' => 'Stark Branch 1', 'code' => 'STARK-1']);

        // Set context to Tenant A
        TenantContext::setTenant($tenantA);
        $branchesSeenByA = Branch::all();

        $this->assertTrue($branchesSeenByA->contains('id', $branchA->id));
        $this->assertFalse($branchesSeenByA->contains('id', $branchB->id));

        // Switch context to Tenant B
        TenantContext::setTenant($tenantB);
        $branchesSeenByB = Branch::all();

        $this->assertTrue($branchesSeenByB->contains('id', $branchB->id));
        $this->assertFalse($branchesSeenByB->contains('id', $branchA->id));
    }
}
