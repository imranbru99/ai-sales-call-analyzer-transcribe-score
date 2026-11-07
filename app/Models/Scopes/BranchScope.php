<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class BranchScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        // If current user is a branch-restricted user (not head office / admin)
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->branch_id && ! $user->isHeadOfficeUser()) {
                $builder->where(function ($query) use ($model, $user) {
                    $query->where($model->qualifyColumn('branch_id'), $user->branch_id)
                        ->orWhereNull($model->qualifyColumn('branch_id'));
                });
            }
        }
    }
}
