@extends('layouts.app')

@section('title', $feature['name'] . ' — AI Sales Call Analyzer')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-12">

    <div>
        <a href="/features" class="text-xs font-bold text-brand-600 hover:underline mb-4 inline-block">&larr; Back to all modules</a>
        <div class="flex items-center gap-3">
            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-white">{{ $feature['name'] }}</h1>
            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-100 text-indigo-700">Module Key: {{ $key }}</span>
        </div>
        <p class="text-base text-gray-600 dark:text-gray-300 mt-4 leading-relaxed">
            {{ $feature['description'] }}
        </p>
    </div>

    <div class="p-8 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm space-y-6">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Architecture & Integration Specification</h3>
        <ul class="space-y-3 text-xs text-gray-600 dark:text-gray-300">
            <li class="flex items-start gap-2">
                <span class="text-brand-600 font-bold">&check;</span>
                <span>Decoupled service provider registered dynamically at runtime based on tenant module status.</span>
            </li>
            <li class="flex items-start gap-2">
                <span class="text-brand-600 font-bold">&check;</span>
                <span>Fully auditable via Spatie ActivityLog with model event tracking and execution history.</span>
            </li>
            <li class="flex items-start gap-2">
                <span class="text-brand-600 font-bold">&check;</span>
                <span>Exposes versioned REST API endpoints under <code class="bg-gray-100 dark:bg-gray-800 px-1 py-0.5 rounded font-mono">/api/v1</code> with HMAC signed webhooks.</span>
            </li>
        </ul>

        <div class="pt-4 flex items-center gap-4">
            <a href="/live-demo" class="px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-sm transition">
                Test in Live Demo &rarr;
            </a>
            <a href="/module-explorer" class="text-xs font-bold text-gray-600 hover:text-gray-900 dark:text-gray-400">
                Configure in Module Explorer
            </a>
        </div>
    </div>

</div>
@endsection
