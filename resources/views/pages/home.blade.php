@extends('layouts.app')

@section('title', 'AI Sales Call Analyzer — Every call transcribed, scored, and turned into revenue')

@section('content')
<div class="space-y-24">

    {{-- HERO SECTION --}}
    <section class="relative pt-12 pb-20 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto space-y-6">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-brand-50 dark:bg-brand-950/40 border border-brand-200 dark:border-brand-800 text-xs font-semibold text-brand-700 dark:text-brand-300">
                    <span class="w-2 h-2 rounded-full bg-brand-600 animate-pulse"></span>
                    Modular Business Intelligence Suite for Call Centers & Sales Teams
                </div>
                <h1 class="text-4xl sm:text-6xl font-extrabold text-gray-900 dark:text-white tracking-tight leading-[1.15]">
                    Every Call Transcribed, Scored, and <span class="bg-gradient-to-r from-brand-600 to-indigo-600 bg-clip-text text-transparent">Turned Into Revenue.</span>
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-300 leading-relaxed">
                    Stop reviewing 2% of calls manually. AI Sales Call Analyzer transcribes 50+ languages, automatically scores calls across custom scorecards (0–100), detects objections and compliance breaches, and synchronizes follow-up tasks to your CRM in seconds.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
                    <a href="/live-demo" class="w-full sm:w-auto px-8 py-3.5 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-xl shadow-lg shadow-brand-500/25 transition">
                        Explore Live Demo (12 Roles) &rarr;
                    </a>
                    <a href="/module-explorer" class="w-full sm:w-auto px-8 py-3.5 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-800 dark:text-gray-200 font-bold rounded-xl border border-gray-200 dark:border-gray-700 transition">
                        Build Your Plan (Module Explorer)
                    </a>
                </div>
            </div>

            {{-- LIVE SCORECARD PREVIEW --}}
            <div class="mt-16 max-w-5xl mx-auto rounded-2xl bg-white dark:bg-gray-900 shadow-2xl border border-gray-200 dark:border-gray-800 p-6 sm:p-8">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 border-b border-gray-100 dark:border-gray-800 gap-4">
                    <div>
                        <div class="text-xs font-bold text-brand-600 uppercase tracking-wider">Live Call Intelligence Sample</div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mt-1">Enterprise Sales Demo — Sarah Jenkins vs Michael Vance</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Duration: 04:12 • Telephony: Twilio SIP Connector • Source: Outbound Campaign</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="text-right">
                            <span class="text-xs font-semibold text-gray-400 uppercase">AI Score</span>
                            <div class="text-3xl font-extrabold text-emerald-600">88.5 <span class="text-sm font-normal text-gray-400">/ 100</span></div>
                        </div>
                        <span class="px-3 py-1 bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400 font-bold rounded-full text-xs">
                            PASSED QA
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-6">
                    <div class="p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl space-y-2 border border-gray-100 dark:border-gray-800">
                        <span class="text-xs font-bold text-gray-500 uppercase">Executive Summary</span>
                        <p class="text-xs text-gray-700 dark:text-gray-300 leading-relaxed">
                            Prospect identified 3-week QA review lag. Rep successfully demonstrated automated scoring and handled budget pushback with ROI calculations.
                        </p>
                    </div>

                    <div class="p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl space-y-2 border border-gray-100 dark:border-gray-800">
                        <span class="text-xs font-bold text-gray-500 uppercase">Talk-to-Listen Ratio</span>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-brand-600 font-bold">Rep 53%</span>
                            <span class="text-emerald-600 font-bold">Customer 47%</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 h-2 rounded-full overflow-hidden flex">
                            <div class="bg-brand-600 h-full w-[53%]"></div>
                            <div class="bg-emerald-500 h-full w-[47%]"></div>
                        </div>
                        <div class="text-[11px] text-gray-400">Optimal balance maintained (within 45–55% target).</div>
                    </div>

                    <div class="p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl space-y-2 border border-gray-100 dark:border-gray-800">
                        <span class="text-xs font-bold text-gray-500 uppercase">Objections Resolved</span>
                        <div class="text-xs font-semibold text-gray-800 dark:text-gray-200">
                            "6 months left on PBX contract"
                        </div>
                        <div class="text-[11px] text-emerald-600 dark:text-emerald-400">
                            &check; Countered via non-intrusive SIP overlay. Next demo set.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- STATS STRIP --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div class="p-6 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800">
                <div class="text-3xl font-extrabold text-brand-600">100%</div>
                <div class="text-xs font-medium text-gray-500 mt-1">Call QA Coverage (vs 2% manual)</div>
            </div>
            <div class="p-6 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800">
                <div class="text-3xl font-extrabold text-brand-600">50+</div>
                <div class="text-xs font-medium text-gray-500 mt-1">Languages Supported with Diarization</div>
            </div>
            <div class="p-6 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800">
                <div class="text-3xl font-extrabold text-brand-600">+34%</div>
                <div class="text-xs font-medium text-gray-500 mt-1">Average Win Rate Increase in 60 Days</div>
            </div>
            <div class="p-6 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800">
                <div class="text-3xl font-extrabold text-brand-600">&lt; 20s</div>
                <div class="text-xs font-medium text-gray-500 mt-1">AI Transcription & Scoring Latency</div>
            </div>
        </div>
    </section>

    {{-- CORE FEATURES GRID --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        <div class="text-center max-w-2xl mx-auto space-y-3">
            <h2 class="text-xs font-bold text-brand-600 uppercase tracking-wider">Comprehensive Architecture</h2>
            <p class="text-3xl font-extrabold text-gray-900 dark:text-white">Every Capability Your Sales Floor Demands</p>
            <p class="text-sm text-gray-500">Each module switches on or off independently from the Module Manager.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="p-6 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 space-y-3">
                <div class="w-10 h-10 rounded-lg bg-indigo-50 dark:bg-indigo-950/50 text-brand-600 flex items-center justify-center font-bold">01</div>
                <h3 class="font-bold text-gray-900 dark:text-white">AI Scoring & Custom Scorecards</h3>
                <p class="text-xs text-gray-500 leading-relaxed">
                    Custom weighted criteria per call type (cold call, demo, support, collections) with pass/fail thresholds and critical-fail triggers.
                </p>
                <a href="/features/ai_scoring" class="text-xs font-bold text-brand-600 hover:underline">Learn more &rarr;</a>
            </div>

            <div class="p-6 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 space-y-3">
                <div class="w-10 h-10 rounded-lg bg-indigo-50 dark:bg-indigo-950/50 text-brand-600 flex items-center justify-center font-bold">02</div>
                <h3 class="font-bold text-gray-900 dark:text-white">Live Assist & Real-Time Guidance</h3>
                <p class="text-xs text-gray-500 leading-relaxed">
                    Live speech-to-text with pop-up objection counter-cards when competitors are mentioned, live talk-meter, and whisper/barge.
                </p>
                <a href="/features/live_assist" class="text-xs font-bold text-brand-600 hover:underline">Learn more &rarr;</a>
            </div>

            <div class="p-6 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 space-y-3">
                <div class="w-10 h-10 rounded-lg bg-indigo-50 dark:bg-indigo-950/50 text-brand-600 flex items-center justify-center font-bold">03</div>
                <h3 class="font-bold text-gray-900 dark:text-white">1:1 Coaching & AI Customer Simulator</h3>
                <p class="text-xs text-gray-500 leading-relaxed">
                    Automated strength/gap identification with personalized drills and realistic interactive role-play practice against an AI prospect.
                </p>
                <a href="/features/coaching" class="text-xs font-bold text-brand-600 hover:underline">Learn more &rarr;</a>
            </div>

            <div class="p-6 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 space-y-3">
                <div class="w-10 h-10 rounded-lg bg-indigo-50 dark:bg-indigo-950/50 text-brand-600 flex items-center justify-center font-bold">04</div>
                <h3 class="font-bold text-gray-900 dark:text-white">Compliance & Automated Redaction</h3>
                <p class="text-xs text-gray-500 leading-relaxed">
                    Phrase rules for mandatory disclosures, forbidden promises, and automated PCI/HIPAA PII redaction across audio and transcripts.
                </p>
                <a href="/features/compliance" class="text-xs font-bold text-brand-600 hover:underline">Learn more &rarr;</a>
            </div>

            <div class="p-6 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 space-y-3">
                <div class="w-10 h-10 rounded-lg bg-indigo-50 dark:bg-indigo-950/50 text-brand-600 flex items-center justify-center font-bold">05</div>
                <h3 class="font-bold text-gray-900 dark:text-white">Biometric Attendance & Shift Hub</h3>
                <p class="text-xs text-gray-500 leading-relaxed">
                    Powered by laravel-attendance-hub: direct hardware integration with ZKTeco, Hikvision, and RFID biometric machines for call centers.
                </p>
                <a href="/features/attendance_hr" class="text-xs font-bold text-brand-600 hover:underline">Learn more &rarr;</a>
            </div>

            <div class="p-6 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 space-y-3">
                <div class="w-10 h-10 rounded-lg bg-indigo-50 dark:bg-indigo-950/50 text-brand-600 flex items-center justify-center font-bold">06</div>
                <h3 class="font-bold text-gray-900 dark:text-white">Multilingual Unicode PDF Reports</h3>
                <p class="text-xs text-gray-500 leading-relaxed">
                    Powered by laravel-unicode-pdf: exports call scorecards, coaching plans, invoices, and payslips in Bengali, Arabic, Hindi, and English.
                </p>
                <a href="/features/analytics_reporting" class="text-xs font-bold text-brand-600 hover:underline">Learn more &rarr;</a>
            </div>
        </div>
    </section>

    {{-- CTA BANNER --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl bg-gradient-to-r from-brand-600 to-indigo-900 p-8 sm:p-14 text-white text-center space-y-6">
            <h2 class="text-3xl sm:text-5xl font-extrabold tracking-tight">Ready to Turn Your Sales Calls Into Pure Revenue?</h2>
            <p class="text-brand-100 max-w-2xl mx-auto text-sm sm:text-base leading-relaxed">
                Test all 12 user panels instantly without filling any forms or entering credit cards.
            </p>
            <div class="pt-4">
                <a href="/live-demo" class="px-8 py-4 bg-white text-brand-700 hover:bg-gray-100 font-extrabold rounded-xl shadow-xl transition inline-block">
                    Launch Interactive Demo Sandbox &rarr;
                </a>
            </div>
        </div>
    </section>

</div>
@endsection
