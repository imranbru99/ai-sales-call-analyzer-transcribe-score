@extends('layouts.app')

@section('title', 'Contact Our Sales & Support Team — AI Sales Call Analyzer')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-8">

    <div class="text-center space-y-4">
        <h1 class="text-3xl sm:text-5xl font-extrabold text-gray-900 dark:text-white">
            Let's Talk Call Intelligence
        </h1>
        <p class="text-base text-gray-600 dark:text-gray-300">
            Have questions about custom scorecards, SIP trunk integration, or enterprise multi-branch deployment?
        </p>
    </div>

    <div class="p-8 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="text-xs font-bold text-gray-500 uppercase">Your Name</label>
                <input type="text" class="mt-1 w-full px-3 py-2 border rounded-lg bg-gray-50 dark:bg-gray-800" placeholder="Jane Doe">
            </div>
            <div>
                <label class="text-xs font-bold text-gray-500 uppercase">Work Email</label>
                <input type="email" class="mt-1 w-full px-3 py-2 border rounded-lg bg-gray-50 dark:bg-gray-800" placeholder="jane@company.com">
            </div>
        </div>
        <div>
            <label class="text-xs font-bold text-gray-500 uppercase">Team Size</label>
            <select class="mt-1 w-full px-3 py-2 border rounded-lg bg-gray-50 dark:bg-gray-800 text-xs">
                <option>1–10 Sales Reps</option>
                <option>11–50 Sales Reps</option>
                <option>51–250 Sales Reps</option>
                <option>250+ Enterprise Seats</option>
            </select>
        </div>
        <div>
            <label class="text-xs font-bold text-gray-500 uppercase">Message / Requirements</label>
            <textarea rows="4" class="mt-1 w-full px-3 py-2 border rounded-lg bg-gray-50 dark:bg-gray-800 text-xs" placeholder="Tell us about your telephony setup..."></textarea>
        </div>
        <button type="button" class="w-full py-3 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-md transition">
            Send Message &rarr;
        </button>
    </div>

</div>
@endsection
