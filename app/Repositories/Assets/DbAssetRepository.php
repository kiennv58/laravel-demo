<?php

namespace App\Repositories\Assets;
use App\Repositories\BaseRepository;

class DbAssetRepository extends BaseRepository implements AssetRepository
{
    public function __construct(Asset $asset)
    {
        $this->model = $asset;
    }

    /**
     * Lấy tất cả bản ghi có phân trang
     *
     * @param  integer $size Số bản ghi mặc định 25
     * @param  array $sorting Sắp xếp
     * @return Illuminate\Pagination\Paginator
     */
    public function getByQuery($params, $size = 25, $sorting = [])
    {
        $query  = array_get($params, 'q', null);
        $type   = array_get($params, 'type', null);

        $model = $this->model;

        if (!empty($sorting)) {
            $model = $model->orderBy($sorting[0], $sorting[1] > 0 ? 'ASC' : 'DESC');
        }
        if (!is_null($type)) {
            $model = $model->where('type', $type);
        }

        if (!is_null($query)) {
            $model = $model->where(function($q) use ($query) {
                return $q->where('name', 'like', "%{$query}%")
                            ->orWhere('description', 'like', "%{$query}%");
            });
        }

        return $size < 0 ? $model->get() : $model->paginate($size);
    }

    public function getAllPage()
    {
    	$model = $this->model->where('type', 1);
    	return $model->get();
    }

}
