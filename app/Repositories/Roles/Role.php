<?php

namespace App\Repositories\Roles;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = ['name', 'display_name', 'description', 'type'];
}
