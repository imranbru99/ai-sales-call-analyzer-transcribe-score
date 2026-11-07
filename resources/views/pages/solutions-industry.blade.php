@extends('layouts.app')

@section('title', 'Industry Solutions — AI Sales Call Analyzer')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-12">

    <div class="text-center max-w-3xl mx-auto space-y-4">
        <h1 class="text-3xl sm:text-5xl font-extrabold text-gray-900 dark:text-white">
            Purpose-Built for Your Specific Industry
        </h1>
        <p class="text-base text-gray-600 dark:text-gray-300">
            Pre-configured scorecards, compliance rules, and objection battlecards tailored for your vertical.
        </p>
    </div>

    <!-- Industry Filter Pills -->
    <div class="flex flex-wrap items-center justify-center gap-2">
        @foreach($industries as $key => $name)
            <a href="/solutions/industry/{{ $key }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $current === $key ? 'bg-brand-600 text-white shadow-md' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:bg-gray-50' }}">
                {{ $name }}
            </a>
        @endforeach
    </div>

    <!-- Active Industry Detail -->
    <div class="p-8 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm max-w-4xl mx-auto space-y-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $industries[$current] ?? 'Industry' }} Optimization</h2>
        <p class="text-xs text-gray-600 dark:text-gray-300 leading-relaxed">
            Our {{ $industries[$current] ?? 'industry' }} pack includes pre-built scorecards covering discovery depth, product pricing explanations, mandatory disclosure phrases, and competitor counter-arguments.
        </p>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4">
            <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-xl space-y-1">
                <span class="text-xs font-bold text-brand-600">Compliance Safeguards</span>
                <p class="text-xs text-gray-500">Automated phrase alerts for regulated claims, recording consent, and terms acceptance.</p>
            </div>
            <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-xl space-y-1">
                <span class="text-xs font-bold text-brand-600">Methodology Framework</span>
                <p class="text-xs text-gray-500">Auto-scores BANT, MEDDIC, or SPICED qualification criteria directly from transcripts.</p>
            </div>
        </div>
        <div class="pt-4">
            <a href="/live-demo" class="px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl transition inline-block">
                Test with {{ $industries[$current] ?? 'Industry' }} Demo Data &rarr;
            </a>
        </div>
    </div>

</div>
@endsection
