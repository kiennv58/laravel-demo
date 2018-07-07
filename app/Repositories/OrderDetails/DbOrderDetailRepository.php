<?php

namespace App\Repositories\OrderDetails;
use App\Repositories\BaseRepository;

class DbOrderDetailRepository extends BaseRepository implements OrderDetailRepository
{
    public function __construct(OrderDetail $orderDetail)
    {
        $this->model = $orderDetail;
    }

    /**
     * Lưu thông tin 1 bản ghi mới
     *
     * @param  array $data
     * @return Eloquent
     */
    public function store($data)
    {
        $model = $this->model->create($data);
        return $this->getById($model->id);
    }

}
