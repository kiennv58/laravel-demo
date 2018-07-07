<?php

namespace App\Repositories\Products;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    const DESIGN = 1;
    const BOOK     = 2;
    const PAGE  = 3;

    const TYPE = [
        self::DESIGN => 'Thiết kế',
        self::BOOK     => 'Có gia công',
        self::PAGE  => 'Không gia công'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = ['product_code', 'name', 'description', 'default_price', 'price_per_page', 'kpi', 'type', 'time_avg'];

    public function getTypes()
    {
    	return self::TYPE;
    }
}
