<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\RestfulHandler;
use App\Http\Controllers\Api\ApiController;

class HelperController extends ApiController
{
    use RestfulHandler;

    protected $userType;

    public function index(Request $request, $name, $option = null)
    {

        $result = [];

        switch ($name) {
            case 'products':
                $result = [
                    'types' => \App\Repositories\Products\Product::TYPE,
                ];
                break;
            case 'orders':
                $result = [
                    'status' => \App\Repositories\Orders\Order::STATUS_LIST,
                ];
                break;
        }

        return response()->json($result, 200);
    }

}
