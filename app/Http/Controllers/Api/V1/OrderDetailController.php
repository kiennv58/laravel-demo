<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\RestfulHandler;
use App\Http\Controllers\Api\TransformerTrait;
use App\Repositories\OrderDetails\OrderDetailRepository;
use App\Http\Transformers\OrderDetailTransformer;

class OrderDetailController extends ApiController
{
	use TransformerTrait, RestfulHandler;

    protected $order_detail;

    protected $validationRules = [
        'order_id'       => 'required',
        'product_id'       => 'required',
        'price'       => 'required'
    ];

    protected $validationMessages = [
        'order_id.required'    => 'Chưa có thông tin đơn hàng',
        'product_id.required'  => 'Vui lòng chọn sản phẩm!',
        'price.required'       => 'Chưa nhập giá!'
    ];

    function __construct(OrderDetailRepository $order_detail, OrderDetailTransformer $transformer)
    {
        $this->order_detail = $order_detail;
        $this->setTransformer($transformer);
        $this->checkPermission('order');
    }

    public function getResource()
    {
        return $this->order_detail;
    }

    // public function index(Request $request)
    // {
    //     $pageSize = $request->get('limit', 25);
    //     $sort = $request->get('sort', 'id:-1');
    //     $params = $request->all();
    //     return $this->successResponse($this->getResource()->getByParam($params, $pageSize, [$sort]));
    // }

    // public function store(Request $request)
    // {
    //     try {
    //         $this->validate($request, $this->rules, $this->messages);
    //         $data = $this->getResource()->store($request->all());
    //         return $this->successResponse($data);
    //     } catch(\Illuminate\Validation\ValidationException $validationException) {
    //         return $this->errorResponse([
    //             'errors' => $validationException->validator->errors(),
    //             'exception' => $validationException->getMessage()
    //         ]);
    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         throw $e;
    //     }
    // }

    // public function update($id, Request $request)
    // {
    //     try {
    //         $this->validate($request, $this->rules, $this->messages);

    //         $order_detail = $this->getResource()->getById($id);

    //         if (is_null($order_detail)) {
    //             return $this->notFoundResponse();
    //         }

    //         $data = $this->getResource()->update($order_detail, $request->all());
    //         return $this->successResponse($data);
    //     } catch (\Illuminate\Validation\ValidationException $validationException) {
    //         return $this->errorResponse([
    //             'errors' => $validationException->validator->errors(),
    //             'exception' => $validationException->getMessage()
    //         ]);
    //     } catch (\Exception $e) {
    //         throw $e;
    //     }
    // }
}
