@extends('layouts.app')

@section('title', $caseStudy->company_name . ' Case Study — AI Sales Call Analyzer')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-8">

    <a href="/case-studies" class="text-xs font-bold text-brand-600 hover:underline">&larr; Back to all case studies</a>

    <div>
        <span class="text-xs font-bold text-brand-600 uppercase">{{ $caseStudy->industry }}</span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-white mt-1">{{ $caseStudy->company_name }}</h1>
        <div class="text-3xl font-extrabold text-emerald-600 mt-3">{{ $caseStudy->metric_highlight }}</div>
    </div>

    <div class="p-8 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm text-sm text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-line">
        {{ $caseStudy->story }}
    </div>

</div>
@endsection
