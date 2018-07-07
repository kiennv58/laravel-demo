<?php

namespace App\Helpers;

use JeroenNoten\LaravelAdminLte\Menu\Builder;
use JeroenNoten\LaravelAdminLte\Menu\Filters\FilterInterface;
use Auth;

class FilterMenuCustomer implements FilterInterface
{
    public function transform($item, Builder $builder)
    {
        if (isset($item['role']) && ! Auth::user()->hasRole($item['role'])) {
            return false;
        }

        return $item;
    }
}