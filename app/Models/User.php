<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property Tenant|null $tenant
 * @property Branch|null $branch
 * @property Team|null $team
 */
class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'tenant_id',
        'branch_id',
        'team_id',
        'name',
        'email',
        'password',
        'phone',
        'role',
        'locale',
        'timezone',
        'avatar_url',
        'is_active',
        'two_factor_enabled',
        'two_factor_secret',
        'preferences',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'two_factor_enabled' => 'boolean',
            'preferences' => 'array',
        ];
    }

    /**
     * @return BelongsTo<Tenant, $this>
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * @return BelongsTo<Branch, $this>
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function calls(): HasMany
    {
        return $this->hasMany(Call::class, 'agent_id');
    }

    public function coachingPlans(): HasMany
    {
        return $this->hasMany(CoachingPlan::class, 'agent_id');
    }

    // Role checks
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isTenantOwner(): bool
    {
        return $this->role === 'tenant_owner';
    }

    public function isBranchManager(): bool
    {
        return $this->role === 'branch_manager';
    }

    public function isSalesManager(): bool
    {
        return $this->role === 'sales_manager';
    }

    public function isAgent(): bool
    {
        return $this->role === 'agent';
    }

    public function isQA(): bool
    {
        return $this->role === 'qa_analyst';
    }

    public function isTrainer(): bool
    {
        return $this->role === 'trainer';
    }

    public function isCompliance(): bool
    {
        return $this->role === 'compliance_officer';
    }

    public function isFinance(): bool
    {
        return $this->role === 'finance';
    }

    public function isHR(): bool
    {
        return $this->role === 'hr_officer';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    public function isReseller(): bool
    {
        return $this->role === 'reseller';
    }

    public function isHeadOfficeUser(): bool
    {
        if ($this->isSuperAdmin() || $this->isTenantOwner()) {
            return true;
        }

        return $this->branch instanceof Branch ? (bool) $this->branch->is_head_office : false;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if (! $this->is_active) {
            return false;
        }

        $panelId = $panel->getId();

        return match ($panelId) {
            'platform' => $this->isSuperAdmin(),
            'business' => in_array($this->role, [
                'super_admin',
                'tenant_owner',
                'branch_manager',
                'sales_manager',
                'qa_analyst',
                'trainer',
                'compliance_officer',
                'finance',
                'hr_officer',
            ]),
            'agent' => in_array($this->role, ['super_admin', 'tenant_owner', 'sales_manager', 'agent']),
            'portal' => in_array($this->role, ['super_admin', 'customer', 'reseller']),
            default => true,
        };
    }
}
