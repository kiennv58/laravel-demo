<?php

namespace App\Helpers;

use Auth;
use LRedis;

class PushNotify
{
    public function push($data)
    {
        $redis = LRedis::connection();
        $redis->publish('order_notify', response()->json($data));
    }
}