<?php

namespace App\Repositories\OrderDetails;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\OrderConstant;

class OrderDetail extends Model
{
	protected $table = 'order_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = ['order_id', 'size', 'quantity', 'page_id', 'page_cover_id', 'tape', 'can', 'spine', 'glue', 'color', 'cover_color', 'plastic', 'odd_page', 'hole', 'size_file', 'product_id', 'price'];

    public function order()
    {
    	return $this->hasOne('App\Repositories\Orders\Order', 'id', 'order_id');
    }

    public function product()
    {
        return $this->hasOne('App\Repositories\Products\Product', 'id', 'product_id');
    }
}
