@extends('layouts.app')

@section('title', 'Customer Success Stories & Case Studies — AI Sales Call Analyzer')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-12">

    <div class="text-center max-w-3xl mx-auto space-y-4">
        <h1 class="text-3xl sm:text-5xl font-extrabold text-gray-900 dark:text-white">
            Proven Results Across High-Velocity Sales Teams
        </h1>
        <p class="text-base text-gray-600 dark:text-gray-300">
            See how leading companies boost quota attainment and eliminate compliance violations.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @foreach($caseStudies as $cs)
            <div class="p-8 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm flex flex-col justify-between space-y-4">
                <div>
                    <span class="text-xs font-bold text-brand-600 uppercase">{{ $cs->industry }}</span>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mt-1">{{ $cs->company_name }}</h3>
                    <div class="text-2xl font-extrabold text-emerald-600 my-3">{{ $cs->metric_highlight }}</div>
                    <p class="text-xs text-gray-500 leading-relaxed">{{ Str::limit($cs->story, 150) }}</p>
                </div>
                <a href="/case-studies/{{ $cs->slug }}" class="text-xs font-bold text-brand-600 hover:underline">
                    Read Case Study &rarr;
                </a>
            </div>
        @endforeach
    </div>

</div>
@endsection
