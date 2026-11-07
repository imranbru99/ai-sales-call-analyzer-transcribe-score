@extends('layouts.app')

@section('title', ucfirst(str_replace('-', ' ', $page)) . ' — AI Sales Call Analyzer')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-6">

    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white capitalize">
        {{ str_replace('-', ' ', $page) }}
    </h1>
    <div class="text-xs text-gray-400">Last updated: {{ date('F d, Y') }} • Effective Immediately</div>

    <div class="p-8 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm text-xs text-gray-700 dark:text-gray-300 leading-relaxed space-y-4">
        <p>
            Welcome to AI Sales Call Analyzer ("the Service"). By accessing or utilizing any part of the platform, you agree to be bound by the terms outlined below.
        </p>
        <h3 class="text-sm font-bold text-gray-900 dark:text-white pt-2">1. Recording Consent & Jurisdiction</h3>
        <p>
            The user agrees to ensure all audio and video recording ingestion adheres to applicable wiretapping and electronic surveillance regulations in the relevant jurisdiction (including all-party consent requirements).
        </p>
        <h3 class="text-sm font-bold text-gray-900 dark:text-white pt-2">2. Data Processing & Privacy</h3>
        <p>
            Customer voice data is processed exclusively for transcription, semantic quality scoring, and coaching feedback. Data is encrypted in transit and at rest using modern encryption standards.
        </p>
        <h3 class="text-sm font-bold text-gray-900 dark:text-white pt-2">3. Service Level & Availability</h3>
        <p>
            Our core infrastructure targets a 99.99% monthly availability SLA, backed by automated redundancy, queued workers, and multi-region backups.
        </p>
    </div>

</div>
@endsection
