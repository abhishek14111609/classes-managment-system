<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait MultiTenant
{
    /**
     * Boot the multi-tenant trait for a model.
     */
    protected static function bootMultiTenant(): void
    {
        if (!app()->runningInConsole() || app()->runningUnitTests()) {
            static::addGlobalScope('school_id', function (Builder $builder) {
                $user = auth()->user();

                if ($user && !$user->isSuperAdmin() && $user->school_id) {
                    $builder->where($builder->getModel()->getTable() . '.school_id', $user->school_id);
                }
            });

            static::creating(function (Model $model) {
                $user = auth()->user();

                if ($user && !$user->isSuperAdmin() && $user->school_id && !$model->school_id) {
                    $model->school_id = $user->school_id;
                }
            });
        }
    }
}
