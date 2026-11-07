<?php

namespace App\Services;

use App\Models\Call;
use App\Models\TranscriptSegment;
use App\Models\User;
use App\Services\Tenancy\TenantContext;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CallIngestionService
{
    public function __construct(
        protected CallAnalysisService $analysisService,
        protected DemoAiDriver $demoAiDriver
    ) {}

    /**
     * Ingest an uploaded audio/video call file.
     */
    public function ingestUploadedFile(
        UploadedFile $file,
        string $title,
        ?int $agentId = null,
        string $callType = 'outbound',
        string $direction = 'outbound',
        ?string $callerNumber = null,
        ?string $calleeNumber = null
    ): Call {
        $tenantId = TenantContext::getTenantId();
        $branchId = TenantContext::getBranchId();

        // 1. Store file in private tenant storage
        $filename = (string) Str::uuid().'.'.$file->getClientOriginalExtension();
        $path = $file->storeAs("calls/{$tenantId}", $filename, 'local');

        // Estimate duration based on size (approx 1MB = 60s for 128kbps mp3)
        $durationSeconds = max(45, (int) round(($file->getSize() / 16000)));

        // 2. Generate normalized waveform points (64 bars)
        $waveform = $this->generateWaveform($durationSeconds);

        // 3. Create Call record
        $call = Call::create([
            'tenant_id' => $tenantId,
            'branch_id' => $branchId,
            'agent_id' => $agentId ?? auth()->id(),
            'title' => $title,
            'call_type' => $callType,
            'direction' => $direction,
            'source' => 'upload',
            'status' => 'processing',
            'caller_number' => $callerNumber ?? '+1 (555) 234-5678',
            'callee_number' => $calleeNumber ?? '+1 (555) 876-5432',
            'audio_path' => $path,
            'audio_disk' => 'local',
            'audio_format' => $file->getClientOriginalExtension(),
            'audio_size_bytes' => $file->getSize(),
            'duration_seconds' => $durationSeconds,
            'waveform_data' => $waveform,
            'consent_recorded' => true,
        ]);

        // 4. Populate transcript segments with diarization
        $agentName = $call->agent instanceof User ? $call->agent->name : 'Sales Agent';
        $segmentsData = $this->demoAiDriver->generateTranscript(
            $agentName,
            'Prospect Client'
        );

        foreach ($segmentsData as $seg) {
            TranscriptSegment::create([
                'call_id' => $call->id,
                'tenant_id' => $call->tenant_id,
                'speaker' => $seg['speaker'],
                'speaker_name' => $seg['speaker_name'],
                'start_time' => $seg['start_time'],
                'end_time' => $seg['end_time'],
                'text' => $seg['text'],
                'sentiment' => $seg['sentiment'],
                'sentiment_score' => $seg['sentiment_score'],
                'confidence' => $seg['confidence'],
            ]);
        }

        // 5. Run full AI scoring & analysis
        return $this->analysisService->analyze($call);
    }

    /**
     * Ingest a synthetic/demo call for seeding or instant testing.
     */
    public function ingestDemoCall(array $attributes): Call
    {
        $durationSeconds = $attributes['duration_seconds'] ?? 180;
        $waveform = $this->generateWaveform($durationSeconds);

        $call = Call::create(array_merge([
            'uuid' => (string) Str::uuid(),
            'status' => 'processing',
            'waveform_data' => $waveform,
            'audio_disk' => 'local',
            'audio_format' => 'mp3',
            'consent_recorded' => true,
        ], $attributes));

        $agentName = $call->agent instanceof User ? $call->agent->name : 'Sarah Jenkins';
        $segmentsData = $attributes['segments'] ?? $this->demoAiDriver->generateTranscript(
            $agentName,
            $attributes['callee_name'] ?? 'Michael Vance'
        );

        foreach ($segmentsData as $seg) {
            TranscriptSegment::create([
                'call_id' => $call->id,
                'tenant_id' => $call->tenant_id,
                'speaker' => $seg['speaker'],
                'speaker_name' => $seg['speaker_name'],
                'start_time' => $seg['start_time'],
                'end_time' => $seg['end_time'],
                'text' => $seg['text'],
                'sentiment' => $seg['sentiment'],
                'sentiment_score' => $seg['sentiment_score'],
                'confidence' => $seg['confidence'],
            ]);
        }

        return $this->analysisService->analyze($call);
    }

    /**
     * Generate 64 normalized visual waveform amplitude points.
     */
    public function generateWaveform(int $durationSeconds): array
    {
        $bars = 64;
        $waveform = [];

        for ($i = 0; $i < $bars; $i++) {
            // Natural speech pattern simulation with peaks and pauses
            $val = sin($i * 0.3) * cos($i * 0.15) * 50 + 45 + rand(-10, 15);
            $waveform[] = max(8, min(100, (int) round($val)));
        }

        return $waveform;
    }
}
