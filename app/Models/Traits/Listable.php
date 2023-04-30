<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TModelClass of Model
 */
trait Listable
{
    /**
     * Sorting scope
     *
     * @param Builder<TModelClass> $query builder object
     * @param string $column DB column to sort
     * @param string $direction desc|asc
     * @return Builder<TModelClass>
     */
    public function scopeSort(Builder $query, string $column = 'created_at', string $direction = 'desc'): Builder
    {
        // Get all sortable columns
        $sortable = $this->getSortableColumns();

        if (in_array($column, $sortable)) {
            return $query->orderBy($column, $direction);
        }

        // If there are no sorting, return query
        return $query;
    }

    /**
     * Filtering like fields scope
     *
     * @param Builder<TModelClass> $query
     * @param array<string, mixed> $filters
     * @return Builder<TModelClass>
     */
    public function scopeFilterLike(Builder $query, array $filters): Builder
    {
        // Get all filterable like columns
        $filterableColumns = $this->getFilterLikeColumns();

        // Filtering happens here
        foreach ($filters as $filter => $value) {
            if (in_array($filter, $filterableColumns)) {
                $query->where($filter, 'like', '%' . $value . '%');
                continue;
            }
        }

        return $query;
    }

    /**
     * Filtering exact fields scope
     *
     * @param Builder<TModelClass> $query
     * @param array<string, mixed> $filters
     * @return Builder<TModelClass>
     */
    public function scopeFilterExact(Builder $query, array $filters): Builder
    {
        // Get all filterable exact columns
        $filterableColumns = $this->getFilterExactColumns();

        // Filtering happens here
        foreach ($filters as $filter => $value) {
            if (in_array($filter, $filterableColumns)) {
                $query->where($filter, $value);
                continue;
            }
        }

        return $query;
    }

    /**
     * Scope to filter both exact and like columns
     *
     * @param Builder<TModelClass> $query
     * @param array<string, mixed> $filters
     * @return Builder<TModelClass>
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query->filterLike($filters)->filterExact($filters);
    }
}
