@extends('layouts.app')

@section('title', 'Solutions by Role — AI Sales Call Analyzer')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-12">

    <div class="text-center max-w-3xl mx-auto space-y-4">
        <h1 class="text-3xl sm:text-5xl font-extrabold text-gray-900 dark:text-white">
            Engineered for Every Stakeholder
        </h1>
        <p class="text-base text-gray-600 dark:text-gray-300">
            From the rep dialing in the trenches to the VP tracking company-wide quota attainment.
        </p>
    </div>

    <div class="flex flex-wrap items-center justify-center gap-2">
        @foreach($roles as $key => $name)
            <a href="/solutions/role/{{ $key }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $current === $key ? 'bg-brand-600 text-white shadow-md' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:bg-gray-50' }}">
                {{ $name }}
            </a>
        @endforeach
    </div>

    <div class="p-8 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm max-w-4xl mx-auto space-y-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $roles[$current] ?? 'Role' }}</h2>
        <p class="text-xs text-gray-600 dark:text-gray-300 leading-relaxed">
            Eliminate guesswork. Empower {{ strtolower($roles[$current] ?? 'your team') }} with automated telemetry, real-time prompts, and granular action items that elevate win rates.
        </p>
        <div class="pt-4">
            <a href="/live-demo" class="px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl transition inline-block">
                Experience Demo for {{ $roles[$current] ?? 'Role' }} &rarr;
            </a>
        </div>
    </div>

</div>
@endsection
