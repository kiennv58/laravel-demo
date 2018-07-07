<?php

namespace App\Repositories\Assets;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
	protected $table = 'assets';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = ['code', 'type', 'name', 'description', 'default_price', 'import_price', 'quantity'];
}
