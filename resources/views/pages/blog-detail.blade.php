@extends('layouts.app')

@section('title', $post->title . ' — AI Sales Call Analyzer')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-8">

    <a href="/blog" class="text-xs font-bold text-brand-600 hover:underline">&larr; Back to all articles</a>

    <div>
        <span class="text-xs font-bold text-brand-600 uppercase">{{ $post->category }}</span>
        <h1 class="text-3xl sm:text-5xl font-extrabold text-gray-900 dark:text-white mt-2 leading-tight">{{ $post->title }}</h1>
        <div class="text-xs text-gray-400 mt-3">Published by <strong>{{ $post->author_name }}</strong> on {{ $post->created_at->format('M d, Y') }}</div>
    </div>

    <div class="p-8 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm text-sm text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-line space-y-4">
        {{ $post->content }}
    </div>

</div>
@endsection
