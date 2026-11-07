@extends('layouts.app')

@section('title', 'Module Explorer & Build Your Plan — AI Sales Call Analyzer')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" 
     x-data="{
        selectedModules: ['call_capture', 'transcription', 'ai_scoring', 'coaching', 'billing_subscriptions', 'security_system', 'website_cms'],
        basePlan: 49,
        presets: {{ json_encode($presets) }},
        modulePrices: {{ json_encode(collect($modules)->mapWithKeys(fn($m, $k) => [$k => $m['price']])) }},
        isModuleSelected(key) {
            return this.selectedModules.includes(key);
        },
        toggleModule(key, isCore) {
            if (isCore) return;
            if (this.selectedModules.includes(key)) {
                this.selectedModules = this.selectedModules.filter(k => k !== key);
            } else {
                this.selectedModules.push(key);
            }
        },
        calculateTotal() {
            let total = this.basePlan;
            this.selectedModules.forEach(k => {
                total += (this.modulePrices[k] || 0);
            });
            return total;
        },
        applyPreset(presetKey) {
            if (this.presets[presetKey]) {
                this.selectedModules = [...this.presets[presetKey].modules];
            }
        }
     }">

    <div class="text-center max-w-3xl mx-auto space-y-4 mb-12">
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-brand-50 dark:bg-brand-950/40 border border-brand-200 dark:border-brand-800 text-xs font-semibold text-brand-700 dark:text-brand-300">
            Modular Pricing Engine
        </div>
        <h1 class="text-3xl sm:text-5xl font-extrabold text-gray-900 dark:text-white">
            Build Your Perfect Call Platform
        </h1>
        <p class="text-base text-gray-600 dark:text-gray-300">
            Only pay for what your sales floor actually uses. Toggle modules ON/OFF below, or choose an industry preset to configure recommended tools with one click.
        </p>
    </div>

    <!-- Preset Selector Strip -->
    <div class="flex flex-wrap items-center justify-center gap-2 mb-10">
        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider mr-2">Quick Presets:</span>
        <button @click="applyPreset('call_center')" class="px-3.5 py-1.5 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700 rounded-lg text-xs font-semibold transition">
            Call Center & BPO (All 22)
        </button>
        <button @click="applyPreset('saas_sales')" class="px-3.5 py-1.5 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700 rounded-lg text-xs font-semibold transition">
            SaaS Sales Team
        </button>
        <button @click="applyPreset('real_estate')" class="px-3.5 py-1.5 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700 rounded-lg text-xs font-semibold transition">
            Real Estate
        </button>
        <button @click="applyPreset('insurance')" class="px-3.5 py-1.5 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700 rounded-lg text-xs font-semibold transition">
            Insurance & Compliance
        </button>
        <button @click="applyPreset('solo_freelancer')" class="px-3.5 py-1.5 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700 rounded-lg text-xs font-semibold transition text-rose-600">
            Solo Closer (Minimal)
        </button>
    </div>

    <!-- Main Content: Modules Grid (Left) & Dynamic Price Summary Card (Right) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <!-- Modules Selection (8 cols) -->
        <div class="lg:col-span-8 grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($modules as $key => $meta)
                <div @click="toggleModule('{{ $key }}', {{ !empty($meta['is_core']) ? 'true' : 'false' }})"
                     :class="isModuleSelected('{{ $key }}') ? 'border-brand-600 bg-brand-50/20 dark:bg-brand-950/20 ring-1 ring-brand-500' : 'border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 opacity-70'"
                     class="p-5 rounded-xl border transition-all cursor-pointer flex flex-col justify-between space-y-3">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold uppercase tracking-wider {{ !empty($meta['is_core']) ? 'text-purple-600' : 'text-gray-400' }}">
                                {{ !empty($meta['is_core']) ? 'Core Module' : ucfirst($meta['category']) }}
                            </span>
                            <div class="flex items-center gap-2">
                                <span class="font-extrabold text-sm text-gray-900 dark:text-white">
                                    {{ $meta['price'] > 0 ? '$' . number_format($meta['price'], 0) . '/mo' : 'Included' }}
                                </span>
                                <input type="checkbox" 
                                       :checked="isModuleSelected('{{ $key }}')" 
                                       {{ !empty($meta['is_core']) ? 'disabled' : '' }}
                                       class="rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                            </div>
                        </div>
                        <h4 class="font-bold text-gray-900 dark:text-white text-base">{{ $meta['name'] }}</h4>
                        <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                            {{ $meta['description'] }}
                        </p>
                    </div>

                    @if(!empty($meta['dependencies']))
                        <div class="text-[11px] text-gray-400 pt-2 border-t border-gray-100 dark:border-gray-800">
                            Requires: <strong>{{ implode(', ', $meta['dependencies']) }}</strong>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Sticky Price Calculator (4 cols) -->
        <div class="lg:col-span-4 sticky top-24">
            <div class="p-6 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-xl space-y-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Estimated Monthly Total</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Custom bundle tailored to your needs</p>
                </div>

                <div class="py-4 border-y border-gray-100 dark:border-gray-800 space-y-3">
                    <div class="flex items-center justify-between text-xs text-gray-600 dark:text-gray-300">
                        <span>Base Platform Platform Seat</span>
                        <span class="font-mono font-bold">$49/mo</span>
                    </div>
                    <div class="flex items-center justify-between text-xs text-gray-600 dark:text-gray-300">
                        <span>Active Modules Selected</span>
                        <span class="font-mono font-bold" x-text="selectedModules.length + ' / 22'"></span>
                    </div>
                    <div class="flex items-center justify-between text-xs text-emerald-600 font-semibold">
                        <span>All Core Modules Included</span>
                        <span>$0</span>
                    </div>
                </div>

                <div class="flex items-baseline justify-between">
                    <span class="text-sm font-semibold text-gray-500">Total Price</span>
                    <div class="text-right">
                        <span class="text-4xl font-extrabold text-brand-600" x-text="'$' + calculateTotal()"></span>
                        <span class="text-xs text-gray-400">/ month</span>
                    </div>
                </div>

                <a href="/live-demo" class="block w-full py-3 bg-brand-600 hover:bg-brand-700 text-white text-center font-bold text-sm rounded-xl shadow-lg shadow-brand-500/25 transition">
                    Test This Configuration Live &rarr;
                </a>

                <p class="text-[11px] text-gray-400 text-center leading-relaxed">
                    Includes 30-day money-back guarantee, 99.99% uptime SLA, and white-glove onboarding.
                </p>
            </div>
        </div>

    </div>

</div>
@endsection
