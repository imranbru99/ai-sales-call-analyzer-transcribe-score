@extends('layouts.app')

@section('title', 'ROI Calculator — Calculate Your Call Intelligence Savings')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-12"
     x-data="{
        reps: 15,
        hoursSpentPerRep: 4,
        avgDealSize: 4500,
        calculateHoursSaved() {
            return Math.round(this.reps * this.hoursSpentPerRep * 4 * 0.85);
        },
        calculateRevenueLift() {
            return Math.round(this.reps * 2 * (this.avgDealSize * 0.25));
        }
     }">

    <div class="text-center space-y-4">
        <h1 class="text-3xl sm:text-5xl font-extrabold text-gray-900 dark:text-white">
            Sales Call Intelligence ROI Calculator
        </h1>
        <p class="text-base text-gray-600 dark:text-gray-300">
            Estimate how much time and quota revenue your organization unlocks by automating QA review.
        </p>
    </div>

    <div class="p-8 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div>
                <label class="text-xs font-bold text-gray-500 uppercase">Sales Reps Count</label>
                <input type="number" x-model="reps" class="mt-2 w-full px-4 py-2 border rounded-lg bg-gray-50 dark:bg-gray-800">
            </div>
            <div>
                <label class="text-xs font-bold text-gray-500 uppercase">Hours spent on QA/rep/wk</label>
                <input type="number" x-model="hoursSpentPerRep" class="mt-2 w-full px-4 py-2 border rounded-lg bg-gray-50 dark:bg-gray-800">
            </div>
            <div>
                <label class="text-xs font-bold text-gray-500 uppercase">Average Deal Size ($)</label>
                <input type="number" x-model="avgDealSize" class="mt-2 w-full px-4 py-2 border rounded-lg bg-gray-50 dark:bg-gray-800">
            </div>
        </div>

        <div class="pt-6 border-t border-gray-100 dark:border-gray-800 grid grid-cols-1 sm:grid-cols-2 gap-6 text-center">
            <div class="p-6 bg-indigo-50 dark:bg-indigo-950/30 rounded-xl">
                <div class="text-3xl font-extrabold text-brand-600" x-text="calculateHoursSaved() + ' Hours'"></div>
                <div class="text-xs font-semibold text-gray-500 mt-1">Management Hours Saved Per Month</div>
            </div>
            <div class="p-6 bg-emerald-50 dark:bg-emerald-950/30 rounded-xl">
                <div class="text-3xl font-extrabold text-emerald-600" x-text="'$' + calculateRevenueLift().toLocaleString()"></div>
                <div class="text-xs font-semibold text-gray-500 mt-1">Estimated Annual Revenue Lift</div>
            </div>
        </div>

        <div class="pt-4 text-center">
            <a href="/live-demo" class="px-6 py-3 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl transition inline-block">
                Start Free Trial &rarr;
            </a>
        </div>
    </div>

</div>
@endsection
