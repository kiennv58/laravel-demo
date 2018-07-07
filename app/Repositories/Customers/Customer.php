<?php

namespace App\Repositories\Customers;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = ['name', 'phone', 'address', 'email'];
}
