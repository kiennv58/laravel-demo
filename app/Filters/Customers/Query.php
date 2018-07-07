<?php

namespace App\Filters\Customers;

use App\Filters\FilterContract;
use Illuminate\Database\Eloquent\Builder;

class Query implements FilterContract
{
    /**
     * Apply a given search value to the builder instance.
     *
     * @param Builder $builder
     * @param mixed $value
     * @return Builder $builder
     */
    public static function apply(Builder $builder, $value)
    {
        if ($value == '')
            return $builder;

        return $builder->where(function($q) use ($value) {
            return $q->where('name', 'like', "%{$value}%")
                ->orWhere('phone', 'like', "%{$value}%");
        });
    }
}
