@extends('layouts.app')

@section('title', 'Transparent Pricing & Plans — AI Sales Call Analyzer')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-16" x-data="{ billingCycle: 'monthly' }">

    <div class="text-center max-w-3xl mx-auto space-y-4">
        <h1 class="text-3xl sm:text-5xl font-extrabold text-gray-900 dark:text-white">
            Simple, Transparent Pricing
        </h1>
        <p class="text-base text-gray-600 dark:text-gray-300">
            Scale seamlessly from a 3-person team to thousands of call center agents. Every plan includes full transcription, AI scoring, and compliance protection.
        </p>

        <!-- Monthly / Yearly Toggle -->
        <div class="pt-4 flex items-center justify-center gap-3">
            <span :class="billingCycle === 'monthly' ? 'text-gray-900 dark:text-white font-bold' : 'text-gray-400'" class="text-sm">Monthly</span>
            <button @click="billingCycle = billingCycle === 'monthly' ? 'yearly' : 'monthly'"
                    class="w-14 h-7 rounded-full bg-brand-600 p-1 relative transition">
                <div :class="billingCycle === 'yearly' ? 'translate-x-7' : 'translate-x-0'"
                     class="w-5 h-5 bg-white rounded-full transition shadow-md"></div>
            </button>
            <span :class="billingCycle === 'yearly' ? 'text-gray-900 dark:text-white font-bold' : 'text-gray-400'" class="text-sm flex items-center gap-1.5">
                Yearly <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Save 20%</span>
            </span>
        </div>
    </div>

    <!-- Pricing Plans Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @foreach($plans as $plan)
            <div class="p-8 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm hover:shadow-xl transition flex flex-col justify-between space-y-6">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $plan->name }}</h3>
                    <p class="text-xs text-gray-500 mt-1">{{ $plan->description }}</p>

                    <div class="mt-6">
                        <span class="text-4xl font-extrabold text-gray-900 dark:text-white"
                              x-text="billingCycle === 'monthly' ? '${{ number_format($plan->price_monthly, 0) }}' : '${{ number_format($plan->price_yearly / 12, 0) }}'"></span>
                        <span class="text-xs text-gray-400">/ seat / month</span>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-800 space-y-3">
                        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Features Included:</div>
                        <ul class="space-y-2 text-xs text-gray-600 dark:text-gray-300">
                            @foreach($plan->features ?? ['Full Automated Transcription', 'Custom AI Scorecards', 'Real-time Objection Detection', 'CRM 2-way sync'] as $feat)
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                    <span>{{ $feat }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <a href="/live-demo" class="block w-full py-3 bg-brand-600 hover:bg-brand-700 text-white text-center font-bold text-xs rounded-xl shadow-md transition">
                    Test Live Demo &rarr;
                </a>
            </div>
        @endforeach
    </div>

    <!-- FAQ Section -->
    <div class="max-w-3xl mx-auto space-y-6 pt-12">
        <h2 class="text-2xl font-bold text-center text-gray-900 dark:text-white">Frequently Asked Questions</h2>
        <div class="space-y-4">
            @foreach($faqs as $faq)
                <div class="p-5 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800">
                    <h4 class="font-bold text-sm text-gray-900 dark:text-white">{{ $faq->question }}</h4>
                    <p class="text-xs text-gray-500 mt-2 leading-relaxed">{{ $faq->answer }}</p>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
