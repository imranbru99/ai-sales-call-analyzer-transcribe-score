@extends('layouts.app')

@section('title', 'Integrations Directory — Telephony, CRMs & Communication')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-12">

    <div class="text-center max-w-3xl mx-auto space-y-4">
        <h1 class="text-3xl sm:text-5xl font-extrabold text-gray-900 dark:text-white">
            Connects With Your Entire Sales Stack
        </h1>
        <p class="text-base text-gray-600 dark:text-gray-300">
            Native integrations with all leading cloud telephony systems, video meeting platforms, CRMs, and team chat.
        </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
        @php
            $integrations = [
                ['name' => 'Twilio', 'category' => 'Telephony', 'status' => 'Verified', 'desc' => 'Native SIP trunk and recording stream connector.'],
                ['name' => 'Zoom Meetings', 'category' => 'Video Conferencing', 'status' => 'Verified', 'desc' => 'Auto-import cloud recordings and speaker diarization.'],
                ['name' => 'Google Meet', 'category' => 'Video Conferencing', 'status' => 'Verified', 'desc' => 'Direct calendar and meeting audio ingestion.'],
                ['name' => 'Microsoft Teams', 'category' => 'Telephony & Video', 'status' => 'Verified', 'desc' => 'Tenant bot and cloud recording sync.'],
                ['name' => 'HubSpot CRM', 'category' => 'CRM', 'status' => 'Verified', 'desc' => 'Two-way sync: deals, contact notes, and call scores.'],
                ['name' => 'Salesforce', 'category' => 'CRM', 'status' => 'Verified', 'desc' => 'Automated call activity logging and stage updates.'],
                ['name' => 'Aircall', 'category' => 'Cloud Phone', 'status' => 'Verified', 'desc' => 'Live call stream and post-call webhook receiver.'],
                ['name' => 'Slack', 'category' => 'Notifications', 'status' => 'Verified', 'desc' => 'Real-time alert channel for high-value buying signals.'],
            ];
        @endphp

        @foreach($integrations as $item)
            <div class="p-6 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-brand-600">{{ $item['category'] }}</span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                        {{ $item['status'] }}
                    </span>
                </div>
                <h3 class="font-bold text-gray-900 dark:text-white text-base">{{ $item['name'] }}</h3>
                <p class="text-xs text-gray-500 leading-relaxed">{{ $item['desc'] }}</p>
            </div>
        @endforeach
    </div>

</div>
@endsection
