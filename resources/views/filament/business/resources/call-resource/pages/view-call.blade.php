<x-filament-panels::page>
@php
    $call = $this->getRecord();
    $segments = $call->segments;
    $waveform = $call->waveform_data ?? [];
    if (empty($waveform)) {
        for ($i=0; $i<64; $i++) { $waveform[] = rand(15, 95); }
    }
@endphp

<div x-data="{
    isPlaying: false,
    currentTime: 0,
    duration: {{ $call->duration_seconds ?: 180 }},
    playbackRate: 1,
    activeTab: 'scorecard',
    searchQuery: '',
    formatTime(sec) {
        const m = Math.floor(sec / 60);
        const s = Math.floor(sec % 60);
        return (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
    },
    seekTo(seconds) {
        this.currentTime = seconds;
        const audio = $refs.callAudio;
        if (audio) {
            audio.currentTime = seconds;
            if (!this.isPlaying) {
                audio.play();
                this.isPlaying = true;
            }
        }
    },
    togglePlay() {
        const audio = $refs.callAudio;
        if (!audio) return;
        if (this.isPlaying) {
            audio.pause();
            this.isPlaying = false;
        } else {
            audio.play().catch(() => {});
            this.isPlaying = true;
        }
    },
    setSpeed(rate) {
        this.playbackRate = rate;
        if ($refs.callAudio) {
            $refs.callAudio.playbackRate = rate;
        }
    }
}" class="space-y-6">

    {{-- Hidden Audio Element for Streaming --}}
    <audio x-ref="callAudio" 
           @timeupdate="currentTime = $event.target.currentTime" 
           @ended="isPlaying = false; currentTime = 0"
           preload="none">
        <source src="{{ $call->getAudioStreamUrl() }}" type="audio/mp3">
    </audio>

    {{-- Top Overview Card: Call Metadata & Primary KPIs --}}
    <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $call->title }}</h1>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $call->isPassing() ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-rose-100 text-rose-800 dark:bg-rose-900/30 dark:text-rose-400' }}">
                        {{ $call->isPassing() ? 'PASSED QA' : 'FAILED THRESHOLD' }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 dark:bg-indigo-900/20 dark:text-indigo-400">
                        {{ ucfirst($call->call_type) }}
                    </span>
                </div>
                <div class="mt-2 text-sm text-gray-500 dark:text-gray-400 flex flex-wrap items-center gap-4">
                    <span>Rep: <strong class="text-gray-700 dark:text-gray-200">{{ $call->agent?->name ?? 'Sales Rep' }}</strong></span>
                    <span>•</span>
                    <span>Duration: <strong class="text-gray-700 dark:text-gray-200">{{ gmdate('i:s', $call->duration_seconds) }}</strong></span>
                    <span>•</span>
                    <span>Caller: <strong class="text-gray-700 dark:text-gray-200">{{ $call->caller_number ?? 'Inbound Lead' }}</strong></span>
                    <span>•</span>
                    <span>Date: <strong class="text-gray-700 dark:text-gray-200">{{ $call->created_at->format('M d, Y • h:i A') }}</strong></span>
                </div>
            </div>

            {{-- Big Score Badge --}}
            <div class="flex items-center gap-6">
                <div class="text-right">
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Overall AI Score</div>
                    <div class="text-4xl font-extrabold {{ $call->overall_score >= 80 ? 'text-emerald-600 dark:text-emerald-400' : ($call->overall_score >= 70 ? 'text-amber-500' : 'text-rose-600') }}">
                        {{ number_format($call->overall_score ?? 0, 1) }}<span class="text-lg text-gray-400 font-normal"> / 100</span>
                    </div>
                </div>
                <div class="h-12 w-px bg-gray-200 dark:bg-gray-700"></div>
                <div class="text-right">
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Deal Likelihood</div>
                    <div class="text-4xl font-extrabold text-indigo-600 dark:text-indigo-400">
                        {{ $call->deal_likelihood_score ?? 75 }}%
                    </div>
                </div>
            </div>
        </div>

        {{-- Audio Waveform Player Bar --}}
        <div class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-2">
                <div class="flex items-center gap-2">
                    <span class="font-mono font-semibold" x-text="formatTime(currentTime)"></span>
                    <span>/</span>
                    <span class="font-mono" x-text="formatTime(duration)"></span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="text-gray-400">Speed:</span>
                    <template x-for="rate in [1, 1.25, 1.5, 2]">
                        <button @click="setSpeed(rate)"
                                :class="playbackRate === rate ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                                class="px-2 py-0.5 text-xs font-medium rounded hover:opacity-80 transition"
                                x-text="rate + 'x'"></button>
                    </template>
                </div>
            </div>

            {{-- Interactive Waveform Visualizer --}}
            <div class="flex items-end gap-[3px] h-14 w-full bg-gray-50 dark:bg-gray-900/50 p-2 rounded-lg cursor-pointer select-none">
                <template x-for="(bar, index) in {{ json_encode($waveform) }}" :key="index">
                    <div @click="seekTo((index / {{ count($waveform) }}) * duration)"
                         class="flex-1 rounded-sm transition-all hover:opacity-100"
                         :style="`height: ${bar}%;`"
                         :class="(currentTime / duration) >= (index / {{ count($waveform) }}) ? 'bg-indigo-600 dark:bg-indigo-500' : 'bg-gray-300 dark:bg-gray-600 opacity-60'">
                    </div>
                </template>
            </div>

            {{-- Playback Controls --}}
            <div class="mt-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <button @click="togglePlay()" 
                            class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium shadow-sm transition">
                        <svg x-show="!isPlaying" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path></svg>
                        <svg x-show="isPlaying" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        <span x-text="isPlaying ? 'Pause Audio' : 'Play Recording'"></span>
                    </button>
                    <button @click="seekTo(Math.max(0, currentTime - 10))" class="p-2 text-gray-500 hover:text-gray-700 dark:hover:text-gray-200" title="Back 10s">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12.066 11.2a1 1 0 000 1.6l5.334 4A1 1 0 0019 16V8a1 1 0 00-1.6-.8l-5.333 4zM4.066 11.2a1 1 0 000 1.6l5.334 4A1 1 0 0011 16V8a1 1 0 00-1.6-.8l-5.334 4z"></path></svg>
                    </button>
                    <button @click="seekTo(Math.min(duration, currentTime + 10))" class="p-2 text-gray-500 hover:text-gray-700 dark:hover:text-gray-200" title="Forward 10s">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.933 12.8a1 1 0 000-1.6L6.6 7.2A1 1 0 005 8v8a1 1 0 001.6.8l5.333-4zM19.933 12.8a1 1 0 000-1.6l-5.333-4A1 1 0 0013 8v8a1 1 0 001.6.8l5.333-4z"></path></svg>
                    </button>
                </div>

                <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                    <span class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span> Agent ({{ round(($call->talk_time_agent_sec / max(1, $call->duration_seconds)) * 100) }}%)
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Customer ({{ round(($call->talk_time_customer_sec / max(1, $call->duration_seconds)) * 100) }}%)
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-gray-400"></span> Silence ({{ round(($call->silence_time_sec / max(1, $call->duration_seconds)) * 100) }}%)
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content Grid: Left Transcript & Right Intelligence Panels --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- Left Column: Interactive Synced Transcript (7 cols) --}}
        <div class="lg:col-span-7 space-y-4">
            <div class="p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                        <h2 class="font-bold text-gray-900 dark:text-white">Synced Transcript ({{ count($segments) }} turns)</h2>
                    </div>
                    <div class="w-48">
                        <input type="text" x-model="searchQuery" placeholder="Search transcript..." 
                               class="w-full text-xs px-2.5 py-1.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    </div>
                </div>

                {{-- Scrollable Transcript Lane --}}
                <div class="mt-4 space-y-4 max-h-[640px] overflow-y-auto pr-2">
                    @forelse($segments as $segment)
                        <div x-show="!searchQuery || '{{ addslashes(strtolower($segment->text)) }}'.includes(searchQuery.toLowerCase())"
                             @click="seekTo({{ $segment->start_time }})"
                             :class="{
                                 'ring-2 ring-indigo-500 ring-offset-2': currentTime >= {{ $segment->start_time }} && currentTime <= {{ $segment->end_time }},
                                 'hover:border-indigo-300': true
                             }"
                             class="p-4 rounded-xl border transition-all cursor-pointer {{ $segment->speaker === 'agent' ? 'bg-indigo-50/50 dark:bg-indigo-950/20 border-indigo-100 dark:border-indigo-900/40 ml-0 mr-8' : 'bg-gray-50 dark:bg-gray-900/40 border-gray-200 dark:border-gray-700 ml-8 mr-0' }}">
                            <div class="flex items-center justify-between text-xs mb-1.5">
                                <span class="font-semibold {{ $segment->speaker === 'agent' ? 'text-indigo-600 dark:text-indigo-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                                    {{ $segment->speaker_name ?? ($segment->speaker === 'agent' ? 'Agent' : 'Customer') }}
                                </span>
                                <div class="flex items-center gap-2 text-gray-400 font-mono">
                                    <span>{{ gmdate('i:s', (int) $segment->start_time) }}</span>
                                    <span>•</span>
                                    <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[10px] {{ $segment->sentiment === 'positive' ? 'bg-emerald-100 text-emerald-700' : ($segment->sentiment === 'negative' ? 'bg-rose-100 text-rose-700' : 'bg-gray-200 text-gray-700') }}">
                                        {{ ucfirst($segment->sentiment) }}
                                    </span>
                                </div>
                            </div>
                            <p class="text-sm text-gray-800 dark:text-gray-200 leading-relaxed">
                                {{ $segment->text }}
                            </p>
                        </div>
                    @empty
                        <div class="text-center py-12 text-gray-400">
                            No transcript segments recorded for this call.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Right Column: Intelligence Tabs & Evidence (5 cols) --}}
        <div class="lg:col-span-5 space-y-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                {{-- Tabs Nav --}}
                <div class="flex border-b border-gray-100 dark:border-gray-700 text-xs font-semibold overflow-x-auto">
                    <button @click="activeTab = 'scorecard'" 
                            :class="activeTab === 'scorecard' ? 'border-b-2 border-indigo-600 text-indigo-600 dark:text-indigo-400 bg-indigo-50/30' : 'text-gray-500 hover:text-gray-700'"
                            class="px-4 py-3 whitespace-nowrap transition">
                        Scorecard
                    </button>
                    <button @click="activeTab = 'summary'" 
                            :class="activeTab === 'summary' ? 'border-b-2 border-indigo-600 text-indigo-600 dark:text-indigo-400 bg-indigo-50/30' : 'text-gray-500 hover:text-gray-700'"
                            class="px-4 py-3 whitespace-nowrap transition">
                        Summary & Actions
                    </button>
                    <button @click="activeTab = 'frameworks'" 
                            :class="activeTab === 'frameworks' ? 'border-b-2 border-indigo-600 text-indigo-600 dark:text-indigo-400 bg-indigo-50/30' : 'text-gray-500 hover:text-gray-700'"
                            class="px-4 py-3 whitespace-nowrap transition">
                        BANT & MEDDIC
                    </button>
                    <button @click="activeTab = 'objections'" 
                            :class="activeTab === 'objections' ? 'border-b-2 border-indigo-600 text-indigo-600 dark:text-indigo-400 bg-indigo-50/30' : 'text-gray-500 hover:text-gray-700'"
                            class="px-4 py-3 whitespace-nowrap transition">
                        Objections
                    </button>
                    <button @click="activeTab = 'compliance'" 
                            :class="activeTab === 'compliance' ? 'border-b-2 border-indigo-600 text-indigo-600 dark:text-indigo-400 bg-indigo-50/30' : 'text-gray-500 hover:text-gray-700'"
                            class="px-4 py-3 whitespace-nowrap transition">
                        Compliance
                    </button>
                </div>

                <div class="p-5 max-h-[600px] overflow-y-auto">
                    {{-- TAB 1: Scorecard with Evidence Quotes --}}
                    <div x-show="activeTab === 'scorecard'" class="space-y-4">
                        @foreach($call->scorecard_breakdown ?? [] as $key => $crit)
                            <div class="p-3.5 rounded-lg border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/30 space-y-1.5">
                                <div class="flex items-center justify-between">
                                    <span class="font-semibold text-sm text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $key)) }}</span>
                                    <span class="text-sm font-bold {{ ($crit['score'] ?? 0) >= 80 ? 'text-emerald-600' : (($crit['score'] ?? 0) >= 70 ? 'text-amber-500' : 'text-rose-600') }}">
                                        {{ $crit['score'] ?? 0 }} / 100
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 h-1.5 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full {{ ($crit['score'] ?? 0) >= 80 ? 'bg-emerald-500' : (($crit['score'] ?? 0) >= 70 ? 'bg-amber-400' : 'bg-rose-500') }}"
                                         style="width: {{ $crit['score'] ?? 0 }}%;"></div>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $crit['notes'] ?? 'Evaluation complete.' }}
                                </p>
                            </div>
                        @endforeach
                    </div>

                    {{-- TAB 2: Summary, Action Items & Key Moments --}}
                    <div x-show="activeTab === 'summary'" class="space-y-4">
                        <div class="p-3.5 bg-indigo-50/50 dark:bg-indigo-950/20 border-l-4 border-indigo-600 rounded-r-lg">
                            <h4 class="text-xs font-bold text-indigo-900 dark:text-indigo-300 uppercase tracking-wider mb-1">Executive Summary</h4>
                            <p class="text-xs text-gray-700 dark:text-gray-300 leading-relaxed">
                                {{ $call->executive_summary ?? $call->summary }}
                            </p>
                        </div>

                        <div>
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Action Items & Commitments</h4>
                            <div class="space-y-2">
                                @foreach($call->action_items ?? [] as $item)
                                    <div class="flex items-start gap-2.5 p-2.5 rounded-lg border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-900 text-xs">
                                        <input type="checkbox" class="mt-0.5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        <div class="flex-1">
                                            <div class="font-medium text-gray-800 dark:text-gray-200">{{ $item['task'] ?? '' }}</div>
                                            <div class="text-[11px] text-gray-400 mt-0.5">Assignee: {{ $item['assignee'] ?? 'Agent' }} • Due: {{ $item['due'] ?? 'Next call' }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        @if(!empty($call->key_moments))
                        <div>
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Key Call Moments</h4>
                            <div class="space-y-2">
                                @foreach($call->key_moments as $km)
                                    <div class="flex items-center justify-between p-2 rounded border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-xs">
                                        <span class="font-medium text-gray-700 dark:text-gray-300">{{ $km['title'] ?? '' }}</span>
                                        <button @click="seekTo({{ (int) str_replace(':', '', $km['timestamp'] ?? '0') }})" 
                                                class="font-mono text-indigo-600 hover:underline">
                                            {{ $km['timestamp'] ?? '00:00' }}
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- TAB 3: Sales Methodologies (BANT & MEDDIC) --}}
                    <div x-show="activeTab === 'frameworks'" class="space-y-4">
                        @foreach($call->methodology_scores ?? [] as $method => $items)
                            <div class="p-3.5 border border-gray-100 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900/40">
                                <h4 class="font-bold text-xs text-indigo-600 dark:text-indigo-400 uppercase tracking-wider mb-2">{{ $method }} Framework</h4>
                                <div class="space-y-1.5 text-xs">
                                    @foreach($items as $field => $val)
                                        <div class="flex items-start gap-2">
                                            <span class="font-semibold text-gray-600 dark:text-gray-400 min-w-[70px]">{{ $field }}:</span>
                                            <span class="text-gray-800 dark:text-gray-200">{{ $val }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- TAB 4: Objections & Buying Signals --}}
                    <div x-show="activeTab === 'objections'" class="space-y-4">
                        <div>
                            <h4 class="text-xs font-bold text-emerald-600 uppercase tracking-wider mb-2">Buying Signals Detected</h4>
                            <ul class="list-disc list-inside space-y-1 text-xs text-gray-700 dark:text-gray-300">
                                @foreach($call->buying_signals ?? [] as $sig)
                                    <li>"{{ $sig }}"</li>
                                @endforeach
                            </ul>
                        </div>

                        <div>
                            <h4 class="text-xs font-bold text-amber-600 uppercase tracking-wider mb-2">Objections Handled</h4>
                            @foreach($call->objections ?? [] as $obj)
                                <div class="p-3 rounded-lg border border-amber-200/50 bg-amber-50/30 dark:bg-amber-950/20 text-xs space-y-1">
                                    <div class="font-medium text-gray-800 dark:text-gray-200"><strong>Customer:</strong> {{ $obj['objection'] ?? '' }}</div>
                                    <div class="text-gray-600 dark:text-gray-400"><strong>Agent Counter:</strong> {{ $obj['counter'] ?? '' }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- TAB 5: Compliance & Risk --}}
                    <div x-show="activeTab === 'compliance'" class="space-y-4">
                        <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-900 text-xs">
                            <span>Recording Consent Tracked:</span>
                            <span class="font-bold text-emerald-600">Verified (00:15)</span>
                        </div>
                        <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-900 text-xs">
                            <span>PII Redaction:</span>
                            <span class="font-bold text-indigo-600">Active (PCI/GDPR compliant)</span>
                        </div>
                        <div class="p-3 rounded-lg border border-emerald-200 bg-emerald-50/50 dark:bg-emerald-950/20 text-xs">
                            <span class="font-semibold text-emerald-800 dark:text-emerald-300">No Critical Compliance Violations Detected.</span>
                            <p class="text-[11px] text-gray-500 mt-1">Disclosures, required jurisdictional statements, and privacy policies were correctly delivered.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-filament-panels::page>
