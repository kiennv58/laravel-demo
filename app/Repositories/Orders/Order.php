<?php

namespace App\Repositories\Orders;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = ['order_code', 'creator_id', 'designer_id', 'producer_id', 'customer_id', 'status', 'kpi', 'deadline'];

    const NEW_ORDER = 0;
    const DESIGN 	= 1;
    const PRODUCING = 2;
    const FINISH 	= 3;
    const CANCEL    = 4;

    const STATUS_LIST = [
    	self::NEW_ORDER => 'Đơn hàng mới',
    	self::DESIGN 	=> 'Đang thiết kế',
    	self::PRODUCING => 'Đang làm',
    	self::FINISH 	=> 'Hoàn thành',
        self::CANCEL    => 'Hủy'
    ];

    public function customer()
    {
        return $this->hasOne('App\Repositories\Customers\Customer', 'id', 'customer_id');
    }

    public function creator()
    {
        return $this->hasOne('App\User', 'id', 'creator_id');
    }

    public function designer()
    {
        return $this->hasOne('App\User', 'id', 'designer_id');
    }

    public function producer()
    {
        return $this->hasOne('App\User', 'id', 'producer_id');
    }

    public function order_details()
    {
        return $this->hasMany('App\Repositories\OrderDetails\OrderDetail', 'order_id');
    }

    public function statusText()
    {
        return self::STATUS_LIST[$this->status];
    }
}
