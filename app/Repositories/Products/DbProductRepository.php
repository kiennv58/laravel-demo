<?php

namespace App\Repositories\Products;
use App\Repositories\BaseRepository;

class DbProductRepository extends BaseRepository implements ProductRepository
{
    public function __construct(Product $product)
    {
        $this->model = $product;
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
        $query  = array_get($params, 'q', '');
        $type   = array_get($params, 'type', '');

        $model = $this->model;

        if ($type) {
            $model = $model->where('type', $type);
        }

        if ($query != '') {
            $model = $model->where(function($q) use ($query) {
                return $q->where('name', 'like', "%{$query}%")
                		->orWhere('description', 'like', "%{$query}%");
            });
        }

        if (!empty($sorting)) {
            $model = $model->orderBy($sorting[0], $sorting[1] > 0 ? 'ASC' : 'DESC');
        }

        return $size < 0 ? $model->get() : $model->paginate($size);
    }

}
