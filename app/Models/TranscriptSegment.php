<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TranscriptSegment extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'call_id',
        'tenant_id',
        'speaker',
        'speaker_name',
        'start_time',
        'end_time',
        'text',
        'sentiment',
        'sentiment_score',
        'confidence',
        'words_data',
        'pii_entities',
        'is_redacted',
    ];

    protected $casts = [
        'start_time' => 'decimal:2',
        'end_time' => 'decimal:2',
        'sentiment_score' => 'decimal:2',
        'confidence' => 'decimal:2',
        'words_data' => 'array',
        'pii_entities' => 'array',
        'is_redacted' => 'boolean',
    ];

    public function call(): BelongsTo
    {
        return $this->belongsTo(Call::class);
    }
}
