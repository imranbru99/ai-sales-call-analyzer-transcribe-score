<?php

namespace Tests\Feature;

use App\Facades\Module;
use App\Models\Call;
use App\Models\Tenant;
use App\Models\User;
use App\Services\CallIngestionService;
use App\Services\PdfReportService;
use App\Services\Tenancy\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EndToEndSystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_public_pages_return_successful_responses(): void
    {
        $urls = [
            '/',
            '/features',
            '/features/ai_scoring',
            '/solutions/industry/saas',
            '/solutions/role/leaders',
            '/pricing',
            '/module-explorer',
            '/live-demo',
            '/integrations',
            '/security',
            '/case-studies',
            '/blog',
            '/compare',
            '/roi-calculator',
            '/contact',
            '/legal/terms',
        ];

        foreach ($urls as $url) {
            $response = $this->get($url);
            $response->assertStatus(200);
        }
    }

    public function test_one_click_demo_login_works_for_all_twelve_roles(): void
    {
        $roles = [
            'super_admin' => '/platform',
            'tenant_owner' => '/app',
            'branch_manager' => '/app',
            'sales_manager' => '/app',
            'agent' => '/agent',
            'qa_analyst' => '/app',
            'trainer' => '/app',
            'compliance_officer' => '/app',
            'finance' => '/app',
            'hr_officer' => '/app',
            'customer' => '/portal',
            'reseller' => '/portal',
        ];

        foreach ($roles as $role => $expectedRedirect) {
            $response = $this->get("/demo-login/{$role}");
            $response->assertRedirect($expectedRedirect);
            $this->assertAuthenticated();
        }
    }

    public function test_call_ingestion_and_ai_scoring_end_to_end(): void
    {
        $tenant = Tenant::where('slug', 'apex')->first();
        TenantContext::setTenant($tenant);

        $ingestionService = app(CallIngestionService::class);
        $agent = User::where('email', 'agent@demo.test')->first();

        $call = $ingestionService->ingestDemoCall([
            'tenant_id' => $tenant->id,
            'agent_id' => $agent->id,
            'title' => 'Automated Integration Verification Call',
            'duration_seconds' => 200,
        ]);

        $this->assertNotNull($call->id);
        $this->assertEquals('scored', $call->status);
        $this->assertNotNull($call->overall_score);
        $this->assertGreaterThan(70, $call->overall_score);
        $this->assertNotEmpty($call->segments);
        $this->assertNotNull($call->summary);
        $this->assertNotNull($call->executive_summary);
        $this->assertNotEmpty($call->action_items);
    }

    public function test_unicode_pdf_report_generates_successfully(): void
    {
        $call = Call::first();
        $this->assertNotNull($call);

        $pdfService = app(PdfReportService::class);
        $path = storage_path('app/test_verification_scorecard.pdf');
        $pdfService->saveCallScorecardPdf($call, $path, 'en');

        $this->assertFileExists($path);
        $this->assertGreaterThan(1000, filesize($path));

        if (file_exists($path)) {
            unlink($path);
        }
    }

    public function test_solo_freelancer_proves_minimal_module_mode(): void
    {
        $solo = Tenant::where('slug', 'solo')->first();
        $this->assertNotNull($solo);

        // Core / included
        $this->assertTrue(Module::enabled('call_capture', $solo));
        $this->assertTrue(Module::enabled('transcription', $solo));

        // Disabled in solo freelancer preset
        $this->assertFalse(Module::enabled('live_assist', $solo));
        $this->assertFalse(Module::enabled('qa_workflow', $solo));
        $this->assertFalse(Module::enabled('attendance_hr', $solo));
        $this->assertFalse(Module::enabled('commission_payroll', $solo));
    }
}
