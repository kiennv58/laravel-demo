<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Builder;

class Sorting {

    public static function apply(array &$sorters, $model)
    {
        return static::applyDecoratorsFromRequest($sorters, $model->newQuery());
    }

    private static function applyDecoratorsFromRequest(array &$sorters, Builder $query)
    {
        if (count($sorters) == 1 && $sorters[0] == '') return $query;

        $model      = $query->getModel();
        $table_name = $model->getTable();
        $nameCache  = "table_{$table_name}";

        $fields = \Cache::remember($nameCache, 43200, function () use ($table_name) {
            return \Schema::getColumnListing($table_name);
        });

        foreach ($sorters as $key => $info) {
            $sorter = explode(':', $info);

            $sorterName = array_get($sorter, 0, '');
            $value = array_get($sorter, 1, 0);

            if (! static::isValidSorter($sorterName, $value, $fields)) {
                unset($sorters[$key]);
                continue;
            }

            $direction = static::getDirection($value);
            $query = $query->orderBy($sorterName, $direction);

        }
        return $query;
    }

    private static function isValidSorter($sorterName, $value, $fields)
    {
        $arrDirection = static::getDirections();

        return in_array($sorterName, $fields) && isset($arrDirection[$value]);
    }

    private static function getDirections()
    {
        return ['ASC' => 'asc', 'DESC' => 'desc'];
    }

    private static function getDirection($value)
    {
        $arrDirection = static::getDirections();

        return $arrDirection[$value];
    }
}
