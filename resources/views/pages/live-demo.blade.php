@extends('layouts.app')

@section('title', 'Live Demo — AI Sales Call Analyzer (One-Click Login for 12 Roles)')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-16">

    <!-- Top Hero Banner -->
    <div class="text-center max-w-3xl mx-auto space-y-4">
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-xs font-semibold text-emerald-700 dark:text-emerald-300">
            <span class="w-2 h-2 rounded-full bg-emerald-600 animate-pulse"></span>
            Zero Setup • Instant 1-Click Access
        </div>
        <h1 class="text-3xl sm:text-5xl font-extrabold text-gray-900 dark:text-white">
            Experience Every Role in Action
        </h1>
        <p class="text-base text-gray-600 dark:text-gray-300 leading-relaxed">
            Click any button below to instantly sign in as that user type. The platform will automatically redirect you to their dedicated role-tailored dashboard with fully populated demo data.
        </p>
    </div>

    <!-- 12 Role Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        <!-- 1. Super Admin -->
        <div class="p-6 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm hover:shadow-md transition flex flex-col justify-between space-y-4">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-800 dark:bg-purple-900/40 dark:text-purple-300">Platform Panel</span>
                    <span class="text-xs text-gray-400 font-mono">/platform</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Super Admin (Platform Owner)</h3>
                <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                    Manages tenants, plans, module catalog, global AI provider keys, system health, and cross-tenant billing.
                </p>
                <div class="mt-3 p-2 bg-gray-50 dark:bg-gray-800/60 rounded text-[11px] font-mono text-gray-600 dark:text-gray-400">
                    Email: <strong>superadmin@demo.test</strong><br>
                    Pass: <strong>password</strong>
                </div>
            </div>
            <a href="/demo-login/super_admin" class="w-full py-2.5 px-4 bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold rounded-lg text-center shadow-sm transition">
                Sign In as Super Admin &rarr;
            </a>
        </div>

        <!-- 2. Tenant Owner -->
        <div class="p-6 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm hover:shadow-md transition flex flex-col justify-between space-y-4">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-300">Business Panel</span>
                    <span class="text-xs text-gray-400 font-mono">/app</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Tenant Owner (Apex Sales Co.)</h3>
                <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                    Controls organization settings, module toggle switches, multi-branch teams, subscription plans, and billing.
                </p>
                <div class="mt-3 p-2 bg-gray-50 dark:bg-gray-800/60 rounded text-[11px] font-mono text-gray-600 dark:text-gray-400">
                    Email: <strong>owner@demo.test</strong><br>
                    Pass: <strong>password</strong>
                </div>
            </div>
            <a href="/demo-login/tenant_owner" class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg text-center shadow-sm transition">
                Sign In as Business Owner &rarr;
            </a>
        </div>

        <!-- 3. Branch Manager -->
        <div class="p-6 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm hover:shadow-md transition flex flex-col justify-between space-y-4">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-300">Business Panel</span>
                    <span class="text-xs text-gray-400 font-mono">/app</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Branch Manager (New York Hub)</h3>
                <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                    Oversees local branch teams, local attendance hardware sync, and branch call volume metrics.
                </p>
                <div class="mt-3 p-2 bg-gray-50 dark:bg-gray-800/60 rounded text-[11px] font-mono text-gray-600 dark:text-gray-400">
                    Email: <strong>branch.manager@demo.test</strong><br>
                    Pass: <strong>password</strong>
                </div>
            </div>
            <a href="/demo-login/branch_manager" class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg text-center shadow-sm transition">
                Sign In as Branch Manager &rarr;
            </a>
        </div>

        <!-- 4. Sales Manager -->
        <div class="p-6 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm hover:shadow-md transition flex flex-col justify-between space-y-4">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-300">Business Panel</span>
                    <span class="text-xs text-gray-400 font-mono">/app</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Sales Manager / Team Lead</h3>
                <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                    Monitors team leaderboards, objection heatmaps, assigns coaching tasks, and tracks deal conversion.
                </p>
                <div class="mt-3 p-2 bg-gray-50 dark:bg-gray-800/60 rounded text-[11px] font-mono text-gray-600 dark:text-gray-400">
                    Email: <strong>manager@demo.test</strong><br>
                    Pass: <strong>password</strong>
                </div>
            </div>
            <a href="/demo-login/sales_manager" class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg text-center shadow-sm transition">
                Sign In as Sales Manager &rarr;
            </a>
        </div>

        <!-- 5. Sales Agent -->
        <div class="p-6 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm hover:shadow-md transition flex flex-col justify-between space-y-4">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">Agent Workspace</span>
                    <span class="text-xs text-gray-400 font-mono">/agent</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Sales Agent / Rep</h3>
                <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                    Focused interface for rep calls, AI feedback, personal scorecards, practice role-plays, and scripts.
                </p>
                <div class="mt-3 p-2 bg-gray-50 dark:bg-gray-800/60 rounded text-[11px] font-mono text-gray-600 dark:text-gray-400">
                    Email: <strong>agent@demo.test</strong><br>
                    Pass: <strong>password</strong>
                </div>
            </div>
            <a href="/demo-login/agent" class="w-full py-2.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg text-center shadow-sm transition">
                Sign In as Sales Agent &rarr;
            </a>
        </div>

        <!-- 6. QA Analyst -->
        <div class="p-6 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm hover:shadow-md transition flex flex-col justify-between space-y-4">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-300">Business Panel</span>
                    <span class="text-xs text-gray-400 font-mono">/app</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">QA Analyst / Reviewer</h3>
                <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                    Review queues, manual scorecard calibration, agent dispute workflows, and score adjustments.
                </p>
                <div class="mt-3 p-2 bg-gray-50 dark:bg-gray-800/60 rounded text-[11px] font-mono text-gray-600 dark:text-gray-400">
                    Email: <strong>qa@demo.test</strong><br>
                    Pass: <strong>password</strong>
                </div>
            </div>
            <a href="/demo-login/qa_analyst" class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg text-center shadow-sm transition">
                Sign In as QA Analyst &rarr;
            </a>
        </div>

        <!-- 7. Trainer / Coach -->
        <div class="p-6 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm hover:shadow-md transition flex flex-col justify-between space-y-4">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-300">Business Panel</span>
                    <span class="text-xs text-gray-400 font-mono">/app</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Trainer & Coach</h3>
                <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                    Builds training courses, learning paths, quizzes, and manages 1:1 coaching plans across teams.
                </p>
                <div class="mt-3 p-2 bg-gray-50 dark:bg-gray-800/60 rounded text-[11px] font-mono text-gray-600 dark:text-gray-400">
                    Email: <strong>trainer@demo.test</strong><br>
                    Pass: <strong>password</strong>
                </div>
            </div>
            <a href="/demo-login/trainer" class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg text-center shadow-sm transition">
                Sign In as Trainer &rarr;
            </a>
        </div>

        <!-- 8. Compliance Officer -->
        <div class="p-6 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm hover:shadow-md transition flex flex-col justify-between space-y-4">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-300">Business Panel</span>
                    <span class="text-xs text-gray-400 font-mono">/app</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Compliance Officer</h3>
                <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                    Sets phrase rules, monitors real-time violations, tracks consent disclosures, and handles PII audit.
                </p>
                <div class="mt-3 p-2 bg-gray-50 dark:bg-gray-800/60 rounded text-[11px] font-mono text-gray-600 dark:text-gray-400">
                    Email: <strong>compliance@demo.test</strong><br>
                    Pass: <strong>password</strong>
                </div>
            </div>
            <a href="/demo-login/compliance_officer" class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg text-center shadow-sm transition">
                Sign In as Compliance Officer &rarr;
            </a>
        </div>

        <!-- 9. Finance / Accountant -->
        <div class="p-6 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm hover:shadow-md transition flex flex-col justify-between space-y-4">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-300">Business Panel</span>
                    <span class="text-xs text-gray-400 font-mono">/app</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Finance & Payroll Officer</h3>
                <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                    Reviews commission plans, auto-calculated deal bonuses, payslips, and subscription invoices.
                </p>
                <div class="mt-3 p-2 bg-gray-50 dark:bg-gray-800/60 rounded text-[11px] font-mono text-gray-600 dark:text-gray-400">
                    Email: <strong>finance@demo.test</strong><br>
                    Pass: <strong>password</strong>
                </div>
            </div>
            <a href="/demo-login/finance" class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg text-center shadow-sm transition">
                Sign In as Finance Officer &rarr;
            </a>
        </div>

        <!-- 10. HR Officer -->
        <div class="p-6 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm hover:shadow-md transition flex flex-col justify-between space-y-4">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-300">Business Panel</span>
                    <span class="text-xs text-gray-400 font-mono">/app</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">HR Officer</h3>
                <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                    Monitors biometric attendance logs (laravel-attendance-hub), agent shift rosters, and punctuality.
                </p>
                <div class="mt-3 p-2 bg-gray-50 dark:bg-gray-800/60 rounded text-[11px] font-mono text-gray-600 dark:text-gray-400">
                    Email: <strong>hr@demo.test</strong><br>
                    Pass: <strong>password</strong>
                </div>
            </div>
            <a href="/demo-login/hr_officer" class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg text-center shadow-sm transition">
                Sign In as HR Officer &rarr;
            </a>
        </div>

        <!-- 11. Customer Portal -->
        <div class="p-6 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm hover:shadow-md transition flex flex-col justify-between space-y-4">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-sky-100 text-sky-800 dark:bg-sky-900/40 dark:text-sky-300">Customer Portal</span>
                    <span class="text-xs text-gray-400 font-mono">/portal</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Customer / Prospect</h3>
                <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                    Reviews shared call action items, meeting transcripts, and provides post-call CSAT survey ratings.
                </p>
                <div class="mt-3 p-2 bg-gray-50 dark:bg-gray-800/60 rounded text-[11px] font-mono text-gray-600 dark:text-gray-400">
                    Email: <strong>customer@demo.test</strong><br>
                    Pass: <strong>password</strong>
                </div>
            </div>
            <a href="/demo-login/customer" class="w-full py-2.5 px-4 bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold rounded-lg text-center shadow-sm transition">
                Sign In as Customer &rarr;
            </a>
        </div>

        <!-- 12. Reseller / Agency -->
        <div class="p-6 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm hover:shadow-md transition flex flex-col justify-between space-y-4">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-sky-100 text-sky-800 dark:bg-sky-900/40 dark:text-sky-300">Reseller Hub</span>
                    <span class="text-xs text-gray-400 font-mono">/portal</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Reseller / Agency Partner</h3>
                <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                    Manages client tenants, custom domain branding, white-label assets, and partner referral commissions.
                </p>
                <div class="mt-3 p-2 bg-gray-50 dark:bg-gray-800/60 rounded text-[11px] font-mono text-gray-600 dark:text-gray-400">
                    Email: <strong>reseller@demo.test</strong><br>
                    Pass: <strong>password</strong>
                </div>
            </div>
            <a href="/demo-login/reseller" class="w-full py-2.5 px-4 bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold rounded-lg text-center shadow-sm transition">
                Sign In as Reseller &rarr;
            </a>
        </div>

    </div>

</div>
@endsection
