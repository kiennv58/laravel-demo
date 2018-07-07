<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class FilterFactory {

    public static function apply(array $filters, $model)
    {
        return static::applyDecoratorsFromRequest($filters, $model->newQuery());
    }

    private static function applyDecoratorsFromRequest(array $filters, Builder $query)
    {
        foreach ($filters as $filterName => $value) {
            $decorator = static::createFilterDecorator($query->getModel(), $filterName);
            if (static::isValidDecorator($decorator)) {
                $query = $decorator::apply($query, $value);
            }
        }
        return $query;
    }

    private static function createFilterDecorator($model, $name)
    {
        /*==================== convert q to query ====================*/
        $name = $name == 'q' ? 'query' : $name;

        $className = get_class($model);
        $reflection = new \ReflectionClass($className);
        return 'App\\Filters\\' . str_plural($reflection->getShortName()) . '\\' . studly_case($name);
    }

    private static function isValidDecorator($decorator)
    {
        return class_exists($decorator);
    }
}
