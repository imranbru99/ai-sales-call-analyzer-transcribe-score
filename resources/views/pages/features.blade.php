@extends('layouts.app')

@section('title', 'Platform Features & Capabilities — AI Sales Call Analyzer')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-16">

    <div class="text-center max-w-3xl mx-auto space-y-4">
        <h1 class="text-3xl sm:text-5xl font-extrabold text-gray-900 dark:text-white">
            22 Modular Capabilities Built for Enterprise Scale
        </h1>
        <p class="text-base text-gray-600 dark:text-gray-300">
            From ingestion of PBX recordings to real-time agent whisper, every module functions as a decoupled, high-performance service.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($modules as $key => $meta)
            <div class="p-6 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm hover:shadow-md transition flex flex-col justify-between space-y-4">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-brand-600">{{ ucfirst($meta['category']) }}</span>
                        @if(!empty($meta['is_core']))
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-100 text-purple-700">Core</span>
                        @endif
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $meta['name'] }}</h3>
                    <p class="text-xs text-gray-500 mt-2 leading-relaxed">{{ $meta['description'] }}</p>
                </div>
                <div class="pt-4 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between">
                    <span class="text-xs font-mono font-bold text-gray-400">key: {{ $key }}</span>
                    <a href="/features/{{ $key }}" class="text-xs font-bold text-brand-600 hover:underline">
                        Explore module &rarr;
                    </a>
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection
