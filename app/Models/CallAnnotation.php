<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CallAnnotation extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'call_id',
        'tenant_id',
        'user_id',
        'timestamp_sec',
        'content',
        'category',
    ];

    protected $casts = [
        'timestamp_sec' => 'decimal:2',
    ];

    public function call(): BelongsTo
    {
        return $this->belongsTo(Call::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
