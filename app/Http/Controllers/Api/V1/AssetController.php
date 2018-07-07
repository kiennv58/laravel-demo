<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\RestfulHandler;
use App\Http\Controllers\Api\TransformerTrait;
use App\Repositories\Assets\AssetRepository;
use App\Http\Transformers\AssetTransformer;

class AssetController extends ApiController
{
	use TransformerTrait, RestfulHandler;

    protected $asset;

    protected $rules = [
        'type'       => 'required|numeric',
        'name'       => 'required'
    ];

    protected $messages = [
        'type.required'      => 'Loại vật tư không được để trống!',
        'type.numeric'       => 'Mã loại vật tư phải ở dạng số!',
        'name.required'      => 'Tên không được để trống!'
    ];

    function __construct(AssetRepository $asset, AssetTransformer $transformer)
    {
        $this->asset = $asset;
        $this->setTransformer($transformer);
        $this->checkPermission('asset');
    }

    public function getResource()
    {
        return $this->asset;
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
            DB::rollback();
            throw $e;
        }
    }

    public function update($id, Request $request)
    {
        try {
            $this->validate($request, $this->rules, $this->messages);

            $asset = $this->asset->getById($id);

            if (is_null($asset)) {
                return $this->notFoundResponse();
            }
            $params = array_only($request->all(), ['code', 'name', 'default_price', 'import_price', 'description']);

            $data = $this->getResource()->update($asset, $params);
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

    public function getAllPage()
    {
        return $this->successResponse($this->getResource()->getAllPage());
    }
}
