<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CsatSurvey extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'call_id',
        'tenant_id',
        'contact_id',
        'score',
        'feedback',
        'channel',
        'responded_at',
    ];

    protected $casts = [
        'score' => 'integer',
        'responded_at' => 'datetime',
    ];

    public function call(): BelongsTo
    {
        return $this->belongsTo(Call::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
