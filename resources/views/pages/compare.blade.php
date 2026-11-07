@extends('layouts.app')

@section('title', 'Compare AI Sales Call Analyzer vs Competitors (Gong, Chorus)')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-12">

    <div class="text-center space-y-4">
        <h1 class="text-3xl sm:text-5xl font-extrabold text-gray-900 dark:text-white">
            Why Teams Choose AI Sales Call Analyzer
        </h1>
        <p class="text-base text-gray-600 dark:text-gray-300">
            Compare side-by-side against legacy sales intelligence and transcription tools.
        </p>
    </div>

    <div class="overflow-hidden border border-gray-200 dark:border-gray-800 rounded-2xl bg-white dark:bg-gray-900 shadow-sm">
        <table class="w-full text-left text-xs border-collapse">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-800/80 border-b border-gray-200 dark:border-gray-800 text-gray-500">
                    <th class="p-4 font-bold">Feature / Capability</th>
                    <th class="p-4 font-bold text-brand-600">AI Sales Call Analyzer</th>
                    <th class="p-4 font-bold">Legacy Tools (Gong / Chorus)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-gray-700 dark:text-gray-300">
                <tr>
                    <td class="p-4 font-semibold">Modular Pricing (Pay Only What You Use)</td>
                    <td class="p-4 text-emerald-600 font-bold">&check; 22 Toggleable Modules</td>
                    <td class="p-4 text-rose-500 font-bold">&cross; Rigid $120+/seat bundles</td>
                </tr>
                <tr>
                    <td class="p-4 font-semibold">Multi-Branch Tenancy with Local Permissions</td>
                    <td class="p-4 text-emerald-600 font-bold">&check; Native Head-Office + Branch Scopes</td>
                    <td class="p-4 text-rose-500 font-bold">&cross; Requires separate enterprise contracts</td>
                </tr>
                <tr>
                    <td class="p-4 font-semibold">Biometric Attendance Machine Hub</td>
                    <td class="p-4 text-emerald-600 font-bold">&check; Native ZKTeco / Hikvision / ADMS</td>
                    <td class="p-4 text-rose-500 font-bold">&cross; Not available</td>
                </tr>
                <tr>
                    <td class="p-4 font-semibold">Multilingual Unicode PDF Reports</td>
                    <td class="p-4 text-emerald-600 font-bold">&check; Bengali, Arabic, Hindi, CJK</td>
                    <td class="p-4 text-rose-500 font-bold">&cross; English only exports</td>
                </tr>
                <tr>
                    <td class="p-4 font-semibold">Self-Hosted or Cloud Storage Options</td>
                    <td class="p-4 text-emerald-600 font-bold">&check; Local, S3, MinIO, Wasabi</td>
                    <td class="p-4 text-rose-500 font-bold">&cross; Vendor cloud locked</td>
                </tr>
            </tbody>
        </table>
    </div>

</div>
@endsection
