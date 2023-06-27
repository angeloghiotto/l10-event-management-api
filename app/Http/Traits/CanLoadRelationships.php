<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder as QueryBuilder;

trait CanLoadRelationships
{
    public function loadRelationships(Model|EloquentBuilder|QueryBuilder|HasMany $for, ?array $relations = null): Model|EloquentBuilder|QueryBuilder|HasMany
    {
        $relations = $relations ?? $this->relations ?? [];
        foreach ($relations as $relation) {
            if ($this->shoudIncludeRelation($relation)) {
                $for->with($relation);
                if ($for instanceof Model) {
                    $for->load($relation);
                } else {
                    $for->with($relation);
                }
            }
        }

        return $for;
    }
    protected function shoudIncludeRelation(string $relation): bool
    {
        if (!request()->has('include')) return false;
        $relations = array_map('trim', explode(',', request()->include));
        return in_array($relation, $relations);
    }
}
