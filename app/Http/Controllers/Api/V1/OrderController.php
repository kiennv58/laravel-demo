<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Repositories\Orders\OrderRepository;
use App\Http\Controllers\Api\RestfulHandler;
use App\Http\Controllers\Api\TransformerTrait;
use App\Repositories\OrderDetails\OrderDetailRepository;
use App\Repositories\Products\ProductRepository;
use App\Repositories\Customers\CustomerRepository;
use App\Http\Transformers\OrderTransformer;
use App\Helpers\OrderConstant;
use App\Repositories\Orders\Order;
use DB;
use Auth;
use Illuminate\Support\Carbon;
use App\Helpers\PushNotify;

class OrderController extends ApiController
{
    use TransformerTrait, RestfulHandler;

    protected $rules = [
        'status'	 => 'required',
        'kpi'		 => 'required|numeric',
        'deadline' => 'required'
    ];

    protected $messages = [
        'status.required' => 'Chưa chọn trạng thái',
        'kpi.required'	  => 'Chiết khấu không được để trống',
        'kpi.numeric'     => 'Chiết khấu chỉ chứa chữ số',
        'deadline.required'    => 'Chưa nhập thời gian'
    ];

    function __construct(OrderRepository $order, ProductRepository $product, CustomerRepository $customer, OrderDetailRepository $order_detail, Order $model, PushNotify $notify, OrderTransformer $transformer)
    {
        $this->order = $order;
        $this->product = $product;
        $this->customer = $customer;
        $this->order_detail = $order_detail;
        $this->model = $model;
        $this->notify = $notify;
        $this->setTransformer($transformer);
        $this->checkPermission('order');
    }

    public function getResource()
    {
        return $this->order;
    }

    public function index(Request $request)
    {
        $pageSize = $request->get('limit', 25);
        $sort = explode(':', $request->get('sort', 'id:1'));
        $params = $request->all();

        return $this->successResponse($this->getResource()->getByQuery($params, $pageSize, $sort));
    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, $this->rules, $this->messages);

            $data = $request->all();
            $data['creator_id'] = Auth::user()->id;
            $data['order_code'] = $this->getCode();
            $data['kpi'] = $data['kpi'] / 100;
            $customer = $this->customer->getOrCreateCustomer([
                            'email' => $data['email'],
                            'name' => $data['name'],
                            'phone' => $data['phone'],
                            'address' => $data['address']
                        ]);
            $data['customer_id'] = $customer->id;

            $order = $this->order->store($data);
            foreach ($data['order_details'] as $key => $order_detail) {
                $order_detail['order_id'] = $order->id;
                $this->order_detail->store($order_detail);
            }
            DB::commit();
            $this->notify->push([
                'title'     => 'Đơn mới tạo',
                'body'      => 'Bởi ' . Auth::user()->name,
                'order_id'  => $order->id
            ]);
            return $this->successResponse($order);
        } catch (\Illuminate\Validation\ValidationException $validationException) {
            DB::rollback();
            return $this->errorResponse([
                'errors' => $validationException->validator->errors(),
                'exception' => $validationException->getMessage()
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $this->validate($request, $this->rules, $this->messages);
            $order = $this->order->getById($id);
            $params = $request->all();
            $params['kpi'] = $params['kpi'] / 100;

            if (is_null($order)) {
                return $this->notFoundResponse();
            }

            DB::beginTransaction();
            $data = $this->order->update($order, $params);
            DB::commit();
            $this->notify->push([
                'title'     => 'Cập nhật đơn',
                'body'      => 'Bởi ' . Auth::user()->name,
                'order_id'  => $order->id
            ]);
            return $this->successResponse($data);
        } catch (\Illuminate\Validation\ValidationException $validationException) {
            DB::rollback();
            return $this->errorResponse([
                'errors' => $validationException->validator->errors(),
                'exception' => $validationException->getMessage()
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function destroy($id)
    {
        $order = $this->getResource()->getById($id);
        if (!$order) {
            return $this->notFoundResponse();
        }

        DB::beginTransaction();

        try {
            $order->order_details()->delete();
            $this->getResource()->delete($order);

            DB::commit();
            $this->notify->push([
                'title'     => 'Xóa đơn hàng',
                'body'      => 'Bởi ' . Auth::user()->name,
                'order_id'  => $order->id
            ]);
            return $this->deleteResponse();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $order = $this->order->getById($id);
            if (is_null($order)) {
                return $this->notFoundResponse();
            }
            $params = array_only($request->all(), ['status']);
            if ($order->status >= $params['status']) {
                return $this->errorResponse(['errors' => ['status' => ['Thông tin cập nhật chưa đúng!']]]);
            }
            if (!array_key_exists($params['status'], Order::STATUS_LIST)) {
                return $this->notFoundResponse();
            }
            $userIds = $this->getUpdateUsers($order->status, $params['status']);
            $params = array_merge($params, $userIds);

            DB::beginTransaction();
            $data = $this->order->update($order, $params);
            DB::commit();
            $this->notify->push([
                'title'     => 'Thay đổi trạng thái đơn',
                'body'      => 'Bởi ' . Auth::user()->name,
                'order_id'  => $order->id
            ]);
            return $this->successResponse($data);
        } catch (\Illuminate\Validation\ValidationException $validationException) {
            DB::rollback();
            return $this->errorResponse([
                'errors' => $validationException->validator->errors(),
                'exception' => $validationException->getMessage()
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function getCode()
    {
        $order = $this->order->getByQuery(['created_at' => date('Y-m-d')], -1, ['created_at', '-1']);
        $totalInDay = count($order->toArray());
        return 'OD' . date('Ymd') . substr(strval(1000 + $totalInDay + 1), 1);
        return count($order->toArray());
    }

    public function getUpdateUsers($oldStatus, $newStatus)
    {
        switch ($newStatus) {
            case Order::DESIGN:
                return ['designer_id' => Auth::user()->id];
                break;
            case Order::PRODUCING:
                if ($oldStatus == 0) {
                    return ['designer_id' => Auth::user()->id, 'producer_id' => Auth::user()->id];
                } else {
                    return ['producer_id' => Auth::user()->id];
                }
                break;
            case Order::FINISH:
                if ($oldStatus == 0) {
                    return ['designer_id' => Auth::user()->id, 'producer_id' => Auth::user()->id];
                } else {
                    return ['producer_id' => Auth::user()->id];
                }
                break;
            case Order::CANCEL:
                return [];
                break;
            default:
                break;
        }
    }
}
