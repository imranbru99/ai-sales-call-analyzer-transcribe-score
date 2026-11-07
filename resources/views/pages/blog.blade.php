@extends('layouts.app')

@section('title', 'Sales Intelligence Insights & Playbooks Blog')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-12">

    <div class="text-center max-w-3xl mx-auto space-y-4">
        <h1 class="text-3xl sm:text-5xl font-extrabold text-gray-900 dark:text-white">
            Sales Intelligence & Call Coaching Blog
        </h1>
        <p class="text-base text-gray-600 dark:text-gray-300">
            Actionable strategies, scorecard templates, and benchmark reports on turning calls into pipeline.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @foreach($posts as $post)
            <div class="p-6 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm flex flex-col justify-between space-y-4">
                <div>
                    <span class="text-xs font-bold text-brand-600 uppercase">{{ $post->category }}</span>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mt-1">{{ $post->title }}</h3>
                    <p class="text-xs text-gray-500 mt-2 leading-relaxed">{{ $post->excerpt }}</p>
                </div>
                <div class="pt-4 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between text-xs">
                    <span class="text-gray-400">By {{ $post->author_name }}</span>
                    <a href="/blog/{{ $post->slug }}" class="font-bold text-brand-600 hover:underline">Read article &rarr;</a>
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection
