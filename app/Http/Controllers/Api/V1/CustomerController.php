<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\RestfulHandler;
use App\Http\Controllers\Api\TransformerTrait;
use App\Repositories\Customers\CustomerRepository;
use App\Http\Transformers\CustomerTransformer;

class CustomerController extends ApiController
{
	use TransformerTrait, RestfulHandler;

    protected $customer;

    protected $rules = [
        'name'       => 'required'
    ];

    protected $messages = [
        'name.required'       => 'Tên không được để trống!'
    ];

    function __construct(CustomerRepository $customer, CustomerTransformer $transformer)
    {
        $this->customer = $customer;
        $this->setTransformer($transformer);
        $this->checkPermission('customer');
    }

    public function getResource()
    {
        return $this->customer;
    }

    public function index(Request $request)
    {
        $pageSize = $request->get('limit', 25);
        $sort = explode(':', $request->get('sort', 'id:1'));

        $models = $this->getResource()->getByQuery($request->all(), $pageSize, $sort);
        return $this->successResponse($models);
    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, $this->rules, $this->messages);
            $data = $this->getResource()->store($request->all());
            return $this->successResponse($data);
        } catch(\Illuminate\Validation\ValidationException $validationException) {
            return $this->errorResponse([
                'errors' => $validationException->validator->errors(),
                'exception' => $validationException->getMessage()
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update($id, Request $request)
    {
        try {
            $this->validate($request, $this->rules, $this->messages);

            $customer = $this->customer->getById($id);

            if (is_null($customer)) {
                return $this->notFoundResponse();
            }

            $data = $this->getResource()->update($customer, $request->all());
            return $this->successResponse($data);
        } catch (\Illuminate\Validation\ValidationException $validationException) {
            return $this->errorResponse([
                'errors' => $validationException->validator->errors(),
                'exception' => $validationException->getMessage()
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
