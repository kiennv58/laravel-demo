<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\RestfulHandler;
use App\Http\Controllers\Api\TransformerTrait;
use App\Repositories\Products\ProductRepository;
use App\Repositories\Products\Product;
use App\Http\Transformers\ProductTransformer;

class ProductController extends ApiController
{
    use TransformerTrait, RestfulHandler;

    protected $product;

    protected $rules = [
        'name'       => 'required',
        'default_price'      => 'numeric'
    ];

    protected $messages = [
        'name.required'       		=> 'Tên không được để trống!',
        'default_price.numeric'		=> 'Giá phải ở dạng số'
    ];

    function __construct(ProductRepository $product, Product $model, ProductTransformer $transformer)
    {
        $this->product = $product;
        $this->model   = $model;
        $this->setTransformer($transformer);
        $this->checkPermission('product');
    }

    public function getResource()
    {
        return $this->product;
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
            $data = $this->getResource()->store($request->all());
            return $this->successResponse($data);
        } catch(\Illuminate\Validation\ValidationException $validationException) {
            return $this->errorResponse([
                'errors' => $validationException->validator->errors(),
                'exception' => $validationException->getMessage()
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function update($id, Request $request)
    {
        try {
            $this->validate($request, $this->rules, $this->messages);

            $product = $this->product->getById($id);

            if (is_null($product)) {
                return $this->notFoundResponse();
            }
            $data = $this->getResource()->update($product, $request->all());
            return $this->successResponse($data);

        } catch(\Illuminate\Validation\ValidationException $validationException) {
            return $this->errorResponse([
                'errors' => $validationException->validator->errors(),
                'exception' => $validationException->getMessage()
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
