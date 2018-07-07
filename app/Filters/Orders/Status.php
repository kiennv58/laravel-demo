<?php

namespace App\Filters\Orders;

use App\Filters\FilterContract;
use Illuminate\Database\Eloquent\Builder;

class Status implements FilterContract
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
        if ($value < 0) {
            return $builder;
        }

        return $builder->where('status', $value);
    }
}
