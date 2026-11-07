@extends('layouts.app')

@section('title', 'Security, Privacy & Data Compliance — AI Sales Call Analyzer')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-12">

    <div class="text-center space-y-4">
        <h1 class="text-3xl sm:text-5xl font-extrabold text-gray-900 dark:text-white">
            Enterprise-Grade Security by Design
        </h1>
        <p class="text-base text-gray-600 dark:text-gray-300">
            Compliant with GDPR, HIPAA, and PCI-DSS standards. Recordings and transcripts are protected with strict tenant isolation.
        </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div class="p-6 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 space-y-2">
            <h3 class="font-bold text-gray-900 dark:text-white">Strict Multi-Tenant Isolation</h3>
            <p class="text-xs text-gray-500 leading-relaxed">
                Global Eloquent scopes strictly partition every query by tenant_id. Tenant A can never view, search, or access Tenant B recordings.
            </p>
        </div>

        <div class="p-6 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 space-y-2">
            <h3 class="font-bold text-gray-900 dark:text-white">Encryption at Rest & In Transit</h3>
            <p class="text-xs text-gray-500 leading-relaxed">
                All voice audio files are stored in private disks with AES-256 encryption. Streaming is accessible only via short-lived HMAC-signed URLs.
            </p>
        </div>

        <div class="p-6 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 space-y-2">
            <h3 class="font-bold text-gray-900 dark:text-white">Automated PII Redaction</h3>
            <p class="text-xs text-gray-500 leading-relaxed">
                Credit card numbers, social security digits, phone numbers, and home addresses are automatically detected and masked in both transcripts and audio.
            </p>
        </div>

        <div class="p-6 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 space-y-2">
            <h3 class="font-bold text-gray-900 dark:text-white">Retention & Right to Erasure</h3>
            <p class="text-xs text-gray-500 leading-relaxed">
                Customizable data retention policies per tenant jurisdiction with one-click GDPR right-to-erasure workflows.
            </p>
        </div>
    </div>

</div>
@endsection
