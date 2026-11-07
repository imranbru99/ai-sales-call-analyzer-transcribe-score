<?php

namespace Database\Seeders;

use App\Facades\Module;
use App\Models\Battlecard;
use App\Models\BlogPost;
use App\Models\Branch;
use App\Models\Call;
use App\Models\Campaign;
use App\Models\CaseStudy;
use App\Models\ComplianceRule;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\Faq;
use App\Models\Invoice;
use App\Models\Plan;
use App\Models\Scorecard;
use App\Models\Script;
use App\Models\SupportTicket;
use App\Models\Team;
use App\Models\Tenant;
use App\Models\Testimonial;
use App\Models\User;
use App\Services\CallIngestionService;
use App\Services\Tenancy\TenantContext;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use ImranDevBd\AttendanceHub\Models\AttendanceDevice;
use ImranDevBd\AttendanceHub\Models\AttendanceLog;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Sync Module Catalog
        Module::syncCatalog();

        // 2. Seed Plans
        $starterPlan = Plan::create([
            'name' => 'Starter Team',
            'slug' => 'starter',
            'description' => 'Ideal for emerging sales teams seeking 100% QA coverage.',
            'price_monthly' => 49.00,
            'price_yearly' => 468.00,
            'max_agents' => 5,
            'max_minutes_per_month' => 1500,
            'is_active' => true,
            'sort_order' => 1,
            'features' => ['Up to 5 Sales Agents', 'Speech-to-Text Transcription', 'AI Scoring (0-100)', 'Standard CRM Sync', 'Email Support'],
        ]);

        $proPlan = Plan::create([
            'name' => 'Growth & Scale',
            'slug' => 'growth',
            'description' => 'For fast-growing organizations requiring live assist and coaching.',
            'price_monthly' => 99.00,
            'price_yearly' => 948.00,
            'max_agents' => 25,
            'max_minutes_per_month' => 8000,
            'is_active' => true,
            'sort_order' => 2,
            'features' => ['Up to 25 Sales Agents', 'Real-Time Live Call Assist', 'Automated 1:1 Coaching', 'Full Compliance Rule Builder', 'Priority Support'],
        ]);

        $enterprisePlan = Plan::create([
            'name' => 'Enterprise & Call Center',
            'slug' => 'enterprise',
            'description' => 'Uncapped intelligence suite with multi-branch tenancy and attendance hardware.',
            'price_monthly' => 199.00,
            'price_yearly' => 1908.00,
            'max_agents' => 500,
            'max_minutes_per_month' => 50000,
            'is_active' => true,
            'sort_order' => 3,
            'features' => ['Unlimited Seats & Branches', 'Biometric Hardware Attendance Hub', 'Dedicated AI Model Fine-Tuning', 'Custom SLAs & 24/7 Phone Support', 'White-Label Branding'],
        ]);

        // 3. Seed Demo Tenants
        // Tenant 1: "Apex Sales Co." (Full call center, all modules ON)
        $apex = Tenant::create([
            'name' => 'Apex Sales Co.',
            'slug' => 'apex',
            'business_type' => 'call_center',
            'status' => 'active',
            'plan_id' => $enterprisePlan->id,
            'trial_ends_at' => now()->addYear(),
            'settings' => ['currency' => 'USD', 'timezone' => 'America/New_York', 'locale' => 'en'],
        ]);
        Module::applyPreset('call_center', $apex);

        // Tenant 2: "Nova Realty" (Real Estate Preset)
        $nova = Tenant::create([
            'name' => 'Nova Realty',
            'slug' => 'nova',
            'business_type' => 'real_estate',
            'status' => 'active',
            'plan_id' => $proPlan->id,
            'trial_ends_at' => now()->addYear(),
            'settings' => ['currency' => 'USD', 'timezone' => 'America/Chicago', 'locale' => 'en'],
        ]);
        Module::applyPreset('real_estate', $nova);

        // Tenant 3: "Solo Closer" (Solo Freelancer Preset - minimal modules to prove modules OFF works)
        $solo = Tenant::create([
            'name' => 'Solo Closer',
            'slug' => 'solo',
            'business_type' => 'solo_freelancer',
            'status' => 'active',
            'plan_id' => $starterPlan->id,
            'trial_ends_at' => now()->addYear(),
            'settings' => ['currency' => 'USD', 'timezone' => 'America/Los_Angeles', 'locale' => 'en'],
        ]);
        Module::applyPreset('solo_freelancer', $solo);

        // 4. Seed Branches for Apex Sales Co.
        $hqBranch = Branch::create([
            'tenant_id' => $apex->id,
            'name' => 'New York Headquarters',
            'code' => 'NYC-HQ',
            'city' => 'New York',
            'country' => 'USA',
            'timezone' => 'America/New_York',
            'is_head_office' => true,
            'status' => 'active',
        ]);

        $londonBranch = Branch::create([
            'tenant_id' => $apex->id,
            'name' => 'London EMEA Branch',
            'code' => 'LDN-01',
            'city' => 'London',
            'country' => 'UK',
            'timezone' => 'Europe/London',
            'is_head_office' => false,
            'status' => 'active',
        ]);

        $sgBranch = Branch::create([
            'tenant_id' => $apex->id,
            'name' => 'Singapore APAC Hub',
            'code' => 'SGP-01',
            'city' => 'Singapore',
            'country' => 'Singapore',
            'timezone' => 'Asia/Singapore',
            'is_head_office' => false,
            'status' => 'active',
        ]);

        // Teams
        $outboundTeam = Team::create([
            'tenant_id' => $apex->id,
            'branch_id' => $hqBranch->id,
            'name' => 'Enterprise Outbound SDRs',
            'description' => 'Targeting Fortune 500 SaaS and Fintech prospects.',
        ]);

        $inboundTeam = Team::create([
            'tenant_id' => $apex->id,
            'branch_id' => $hqBranch->id,
            'name' => 'Inbound Qualified Closers',
            'description' => 'Handling product demo requests and fast inbound leads.',
        ]);

        // 5. Seed All 12 Mandatory Demo Users (Known credentials from spec)
        $password = Hash::make('password');

        // (1) Super Admin
        $superadmin = User::create([
            'tenant_id' => null, // platform level
            'name' => 'Imran Ahmed (Platform Admin)',
            'email' => 'superadmin@demo.test',
            'password' => $password,
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        // (2) Tenant Owner
        $owner = User::create([
            'tenant_id' => $apex->id,
            'branch_id' => $hqBranch->id,
            'name' => 'Arthur Pendelton (CEO)',
            'email' => 'owner@demo.test',
            'password' => $password,
            'role' => 'tenant_owner',
            'is_active' => true,
        ]);

        // (3) Branch Manager
        $branchManager = User::create([
            'tenant_id' => $apex->id,
            'branch_id' => $hqBranch->id,
            'name' => 'Victoria Sterling',
            'email' => 'branch.manager@demo.test',
            'password' => $password,
            'role' => 'branch_manager',
            'is_active' => true,
        ]);

        // (4) Sales Manager
        $salesManager = User::create([
            'tenant_id' => $apex->id,
            'branch_id' => $hqBranch->id,
            'team_id' => $outboundTeam->id,
            'name' => 'Marcus Vance (Sales Director)',
            'email' => 'manager@demo.test',
            'password' => $password,
            'role' => 'sales_manager',
            'is_active' => true,
        ]);
        $outboundTeam->update(['manager_id' => $salesManager->id]);

        // (5) Sales Agent
        $agent = User::create([
            'tenant_id' => $apex->id,
            'branch_id' => $hqBranch->id,
            'team_id' => $outboundTeam->id,
            'name' => 'Sarah Jenkins (Top Closer)',
            'email' => 'agent@demo.test',
            'password' => $password,
            'role' => 'agent',
            'is_active' => true,
        ]);

        // (6) QA Analyst
        $qa = User::create([
            'tenant_id' => $apex->id,
            'branch_id' => $hqBranch->id,
            'name' => 'Elena Rostova (QA Lead)',
            'email' => 'qa@demo.test',
            'password' => $password,
            'role' => 'qa_analyst',
            'is_active' => true,
        ]);

        // (7) Trainer / Coach
        $trainer = User::create([
            'tenant_id' => $apex->id,
            'branch_id' => $hqBranch->id,
            'name' => 'David Kross (Enablement Coach)',
            'email' => 'trainer@demo.test',
            'password' => $password,
            'role' => 'trainer',
            'is_active' => true,
        ]);

        // (8) Compliance Officer
        $compliance = User::create([
            'tenant_id' => $apex->id,
            'branch_id' => $hqBranch->id,
            'name' => 'Rachel Zhang (Legal & Risk)',
            'email' => 'compliance@demo.test',
            'password' => $password,
            'role' => 'compliance_officer',
            'is_active' => true,
        ]);

        // (9) Finance Officer
        $finance = User::create([
            'tenant_id' => $apex->id,
            'branch_id' => $hqBranch->id,
            'name' => 'Thomas Miller (Controller)',
            'email' => 'finance@demo.test',
            'password' => $password,
            'role' => 'finance',
            'is_active' => true,
        ]);

        // (10) HR Officer
        $hr = User::create([
            'tenant_id' => $apex->id,
            'branch_id' => $hqBranch->id,
            'name' => 'Sophia Martinez (People Ops)',
            'email' => 'hr@demo.test',
            'password' => $password,
            'role' => 'hr_officer',
            'is_active' => true,
        ]);

        // (11) Customer Portal User
        $customer = User::create([
            'tenant_id' => $apex->id,
            'name' => 'Michael Vance (Acme Corp VP)',
            'email' => 'customer@demo.test',
            'password' => $password,
            'role' => 'customer',
            'is_active' => true,
        ]);

        // (12) Reseller / Agency Partner
        $reseller = User::create([
            'tenant_id' => $apex->id,
            'name' => 'Global Sales Partners Agency',
            'email' => 'reseller@demo.test',
            'password' => $password,
            'role' => 'reseller',
            'is_active' => true,
        ]);

        // Additional agents for realistic team size
        $agentNames = [
            'Liam O\'Connor', 'Emma Watson', 'Noah Bennett', 'Olivia Taylor',
            'Lucas Hayes', 'Sophia Patel', 'Jackson Reed', 'Aria Montgomery',
            'Ethan Morales', 'Chloe Nguyen', 'Benjamin Brooks', 'Harper Davis',
        ];
        $extraAgents = [];
        foreach ($agentNames as $name) {
            $slug = Str::slug($name);
            $extraAgents[] = User::create([
                'tenant_id' => $apex->id,
                'branch_id' => $hqBranch->id,
                'team_id' => $outboundTeam->id,
                'name' => $name,
                'email' => "{$slug}@apexsales.demo",
                'password' => $password,
                'role' => 'agent',
                'is_active' => true,
            ]);
        }

        // 6. Scorecards
        $demoScorecard = Scorecard::create([
            'tenant_id' => $apex->id,
            'name' => 'Enterprise Sales Demo Evaluation (V2.1)',
            'call_type' => 'sales_demo',
            'description' => 'Evaluates discovery depth, active listening, objection handling, ROI articulation, and closing.',
            'is_active' => true,
            'pass_threshold' => 75.00,
            'criteria' => [
                ['key' => 'greeting', 'title' => 'Greeting & Agenda Setting', 'weight' => 10, 'is_critical' => false],
                ['key' => 'discovery', 'title' => 'Pain Points & Need Analysis', 'weight' => 20, 'is_critical' => false],
                ['key' => 'active_listening', 'title' => 'Active Listening & Empathy', 'weight' => 15, 'is_critical' => false],
                ['key' => 'objection_handling', 'title' => 'Objection Countering & Proof Points', 'weight' => 20, 'is_critical' => false],
                ['key' => 'pitch_quality', 'title' => 'Solution Value Alignment', 'weight' => 15, 'is_critical' => false],
                ['key' => 'compliance', 'title' => 'Recording Disclosure & Privacy Notice', 'weight' => 10, 'is_critical' => true],
                ['key' => 'closing', 'title' => 'Firm Next Step & Calendar Confirmation', 'weight' => 10, 'is_critical' => false],
            ],
        ]);

        // 7. Compliance Rules
        ComplianceRule::create([
            'tenant_id' => $apex->id,
            'name' => 'Mandatory Recording Consent',
            'rule_type' => 'mandatory_disclosure',
            'phrases' => ['call is recorded', 'recorded for quality', 'recording notice'],
            'severity' => 'critical',
            'industry_pack' => 'finance',
            'is_active' => true,
        ]);

        ComplianceRule::create([
            'tenant_id' => $apex->id,
            'name' => 'Forbidden Guarantee Claims',
            'rule_type' => 'forbidden_phrase',
            'phrases' => ['guaranteed 100% return', 'zero risk', 'foolproof profit'],
            'severity' => 'high',
            'industry_pack' => 'finance',
            'is_active' => true,
        ]);

        // 8. CRM Contacts & Deals
        $contact = Contact::create([
            'tenant_id' => $apex->id,
            'branch_id' => $hqBranch->id,
            'assigned_agent_id' => $agent->id,
            'name' => 'Michael Vance',
            'email' => 'michael.vance@acmecorp.com',
            'phone' => '+1 (555) 234-5678',
            'company_name' => 'Acme Corporation',
            'job_title' => 'VP of Global Operations',
            'status' => 'prospect',
            'lead_score' => 88,
        ]);

        Deal::create([
            'tenant_id' => $apex->id,
            'branch_id' => $hqBranch->id,
            'contact_id' => $contact->id,
            'agent_id' => $agent->id,
            'name' => 'Acme Corp — 50-Seat Intelligence Rollout',
            'amount' => 45000.00,
            'currency' => 'USD',
            'pipeline' => 'Enterprise Pipeline',
            'stage' => 'proposal',
            'probability' => 85,
            'status' => 'open',
            'close_date' => now()->addDays(20),
            'ai_summary' => 'High-likelihood enterprise deal following successful demo call with Sarah Jenkins.',
        ]);

        // 9. Campaigns & Playbooks
        Campaign::create([
            'tenant_id' => $apex->id,
            'branch_id' => $hqBranch->id,
            'name' => 'Q4 Enterprise Outbound Campaign',
            'dialer_mode' => 'preview',
            'status' => 'active',
            'caller_id' => '+1 (800) 555-APEX',
            'total_leads' => 450,
            'completed_leads' => 280,
            'connection_rate' => 38.5,
            'conversion_rate' => 14.2,
        ]);

        Script::create([
            'tenant_id' => $apex->id,
            'title' => 'Cold Outbound SaaS Script (Branching Flow)',
            'slug' => 'cold-outbound-saas',
            'call_type' => 'cold_call',
            'version' => '2.0',
            'content' => "Hi [Name], this is [Rep] with Apex Sales. The reason for my call is we're helping teams like [Company] eliminate manual call reviews...",
            'is_active' => true,
        ]);

        Battlecard::create([
            'tenant_id' => $apex->id,
            'competitor_name' => 'Gong.io',
            'slug' => 'gong-vs-apex',
            'competitor_strengths' => 'Well-known brand in enterprise market.',
            'our_advantages' => 'Full modular freedom (toggle modules off), lower TCO, native biometric attendance hub, and multilingual PDF support.',
            'counter_points' => [
                'Pricing: Gong locks into 3-year enterprise contracts with mandatory platform fees.',
                'Flexibility: Our platform allows disabling unused modules so smaller branches do not overpay.',
            ],
            'auto_researched_at' => now(),
        ]);

        // 10. Ingest Demo Calls with Transcripts & AI Scoring
        TenantContext::setTenant($apex);
        TenantContext::setBranch($hqBranch);
        $ingestionService = app(CallIngestionService::class);

        $demoCallTitles = [
            'Enterprise Sales Demo — Sarah Jenkins vs Michael Vance',
            'Follow-up Call: Pricing ROI Breakdown — CloudScale Systems',
            'Inbound Inquiries: Multi-Branch Telephony Setup — FinTech Global',
            'Cold Outreach: Discovery & Problem Identification — Nexus Health',
            'Contract Renewal Negotiation — Metro Logistics UK',
            'Quarterly Account Review & Expansion — Skyline Realty',
        ];

        foreach ($demoCallTitles as $index => $callTitle) {
            $ingestionService->ingestDemoCall([
                'tenant_id' => $apex->id,
                'branch_id' => $hqBranch->id,
                'agent_id' => $agent->id,
                'customer_id' => $contact->id,
                'scorecard_id' => $demoScorecard->id,
                'title' => $callTitle,
                'call_type' => $index % 2 === 0 ? 'sales_demo' : 'outbound',
                'direction' => 'outbound',
                'source' => 'twilio',
                'caller_number' => '+1 (555) 234-5678',
                'callee_number' => '+1 (555) 876-5432',
                'duration_seconds' => 240 + ($index * 45),
            ]);
        }

        // 11. Public Website CMS Data (Posts, Testimonials, Case Studies, FAQs)
        Testimonial::create([
            'author_name' => 'Marcus Vance',
            'author_title' => 'VP of Revenue',
            'company' => 'CloudScale Analytics',
            'quote' => 'AI Sales Call Analyzer transformed our 40-person call center. We went from auditing 15 calls a month to reviewing 100% of calls in real time. Our win rate surged by 34% within 60 days.',
            'rating' => 5,
            'is_featured' => true,
        ]);

        Testimonial::create([
            'author_name' => 'Elena Rostova',
            'author_title' => 'Chief Compliance Officer',
            'company' => 'EuroFin Trust',
            'quote' => 'The compliance phrase detection and automatic PII redaction saved us hundreds of hours of manual audit. It is the most complete sales intelligence suite we evaluated.',
            'rating' => 5,
            'is_featured' => true,
        ]);

        CaseStudy::create([
            'company_name' => 'Metro Logistics Global',
            'slug' => 'metro-logistics',
            'industry' => 'Transportation & Logistics',
            'metric_highlight' => '+42% Objection Handling Success',
            'story' => 'Metro Logistics deployed AI Sales Call Analyzer across 12 branch offices. By leveraging real-time objection battlecards and automated daily coaching drills, reps overcame pricing pushback with consistent ROI arguments.',
            'is_featured' => true,
        ]);

        CaseStudy::create([
            'company_name' => 'FinTech Pro',
            'slug' => 'fintech-pro',
            'industry' => 'Banking & Microfinance',
            'metric_highlight' => '0 Regulatory Disclosure Fines',
            'story' => 'Operating under strict financial disclosure requirements, FinTech Pro utilized our automated compliance phrase detector. 100% of agent calls now adhere strictly to regulatory opening disclosures.',
            'is_featured' => true,
        ]);

        BlogPost::create([
            'title' => 'How to Build an AI-Driven Sales QA Scorecard That Actually Drives Revenue',
            'slug' => 'build-ai-sales-qa-scorecard',
            'excerpt' => 'Why traditional check-the-box scorecards fail and how category weighting, critical-fail rules, and evidence timestamps coach reps effectively.',
            'content' => 'Sales teams often struggle with inconsistent QA. When only 2% of calls are checked, agents drift from the playbook. Here is how modern sales leaders structure weighted scorecards with automated evidence extraction...',
            'author_name' => 'Imran Ahmed',
            'category' => 'Sales Intelligence',
            'is_published' => true,
            'published_at' => now()->subDays(3),
        ]);

        BlogPost::create([
            'title' => 'The Science of Talk-to-Listen Ratios: What 100,000 Sales Calls Reveal',
            'slug' => 'science-of-talk-listen-ratios',
            'excerpt' => 'Top performers do not dominate the conversation—they ask targeted discovery questions and maintain a 45–55% talk balance.',
            'content' => 'Analyzing over 100,000 sales calls reveals a consistent pattern: deals close when prospects talk at least 45% of the total call duration. Monologues longer than 90 seconds correlate directly with deal stall...',
            'author_name' => 'Imran Ahmed',
            'category' => 'Call Coaching',
            'is_published' => true,
            'published_at' => now()->subDays(7),
        ]);

        Faq::create([
            'question' => 'How does the modular system work for our business?',
            'answer' => 'Every tenant organization has access to the Module Manager. You can toggle off any module (e.g. dialer, commissions, or live assist) and it disappears completely from your navigation, routes, and billing.',
            'category' => 'General',
            'sort_order' => 1,
            'is_published' => true,
        ]);

        Faq::create([
            'question' => 'Does this require expensive third-party AI API keys?',
            'answer' => 'No. While you can plug in your own OpenAI, Gemini, or Claude API keys via laravel-ai-hub for live external AI models, the application includes a deterministic offline AI intelligence driver for out-of-the-box testing and zero-cost operation.',
            'category' => 'General',
            'sort_order' => 2,
            'is_published' => true,
        ]);

        Faq::create([
            'question' => 'How does the multi-branch permission system isolate data?',
            'answer' => 'Global Eloquent scopes enforce branch_id checks automatically. Agents and branch managers only see calls, leads, and rosters belonging to their branch, while Head Office executives can monitor all branches company-wide.',
            'category' => 'Security',
            'sort_order' => 3,
            'is_published' => true,
        ]);

        // 12. Support, Attendance & Billing
        $attendanceDevice = new AttendanceDevice;
        $attendanceDevice->tenant_id = (string) $apex->id;
        $attendanceDevice->branch_id = $hqBranch->id;
        $attendanceDevice->name = 'HQ Main Entrance Biometric Device (ZKTeco ProFace X)';
        $attendanceDevice->provider = 'zkteco';
        $attendanceDevice->model = 'ProFace X';
        $attendanceDevice->ip = '192.168.1.100';
        $attendanceDevice->port = 4370;
        $attendanceDevice->status = 'online';
        $attendanceDevice->last_seen_at = now();
        $attendanceDevice->save();

        AttendanceLog::create([
            'tenant_id' => (string) $apex->id,
            'device_id' => $attendanceDevice->id,
            'device_user_id' => (string) $agent->id,
            'employee_id' => 'EMP-001',
            'punched_at' => now()->startOfDay()->addHours(8)->addMinutes(54),
            'punch_type' => 'in',
            'verify_mode' => 'face',
            'punch_hash' => hash('sha256', "1-{$agent->id}-2026-11-07-0854"),
        ]);

        Invoice::create([
            'tenant_id' => $apex->id,
            'invoice_number' => 'INV-2026-001',
            'amount' => 199.00,
            'tax' => 19.90,
            'total' => 218.90,
            'currency' => 'USD',
            'status' => 'paid',
            'gateway' => 'stripe',
            'paid_at' => now(),
        ]);

        SupportTicket::create([
            'tenant_id' => $apex->id,
            'user_id' => $owner->id,
            'subject' => 'Assistance with custom scorecard calibration for Q4',
            'priority' => 'medium',
            'status' => 'in_progress',
            'department' => 'Customer Success',
        ]);
    }
}
