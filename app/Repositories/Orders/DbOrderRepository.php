<?php

namespace App\Repositories\Orders;
use App\Repositories\BaseRepository;

class DbOrderRepository extends BaseRepository implements OrderRepository
{
    public function __construct(Order $order)
    {
        $this->model = $order;
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
        $query       = array_get($params, 'q', null);
        $status      = array_get($params, 'status', null);
        $staff       = array_get($params, 'staff', null);
        $date        = array_get($params, 'created_at', null);
        $startDate   = array_get($params, 'start_date', null);
        $endDate     = array_get($params, 'end_date', null);

        $model = $this->model;

        if (!is_null($status)) {
            $model = $model->where('status', $status);
        }

        if ($staff) {
            $model = $model->where('creator_id', $staff)
                            ->orWhere('designer_id', $staff)
                            ->orWhere('producer_id', $staff);
        }

        if (!is_null($date)) {
            $model = $model->whereDate('created_at', $date);
        }
        if (!is_null($startDate)) {
            $model = $model->whereDate('created_at', '>=', $startDate);
        }
        if (!is_null($endDate)) {
            $model = $model->whereDate('created_at', '<=', $endDate);
        }

        if ($query != '') {
            $model = $model->where(function($q) use ($query) {
                return $q->where('order_code', 'like', "%{$query}%");
            });
        }

        if (!empty($sorting)) {
            $model = $model->orderBy($sorting[0], $sorting[1] > 0 ? 'ASC' : 'DESC');
        }

        return $size < 0 ? $model->get() : $model->paginate($size);
    }

    public function numberTicketsBooked($params)
    {
        $sDate = array_get($params, 'start_date', null);
        $eDate = array_get($params, 'end_date', null);
        $userId = array_get($params, 'user_id', null);
        $view = array_get($params, 'view', 'day');

        $model = $this->model->orderBy('id', 'ASC');

        if (! is_null($sDate)) {
            $sDate .= ' 00:00:00';
            $model = $model->where('created_at', '>=', $sDate);
        }

        if (! is_null($eDate)) {
            $eDate .= ' 23:59:59';
            $model = $model->where('created_at', '<=', $eDate);
        }

        if (! is_null($userId)) {
            if (! is_numeric($userId)) {
                $userId = \Hashids::decode($userId)[0];
            }
            $model = $model->where('creator_id', $userId)
                            ->orWhere('designer_id', $userId)
                            ->orWhere('producer_id', $userId);
        }

        switch ($view) {
            case 'month':
                $dataFormat = 'DATE_FORMAT(created_at, "%m-%Y")';
                break;

            case 'year':
                $dataFormat = 'DATE_FORMAT(created_at, "%Y")';
                break;

            case 'week':
                $dataFormat =  'CONCAT(DATE_FORMAT(DATE_ADD(created_at, INTERVAL(1-DAYOFWEEK(created_at)) DAY), "%d-%m-%Y"), " - ", DATE_FORMAT(DATE_ADD(created_at, INTERVAL(7-DAYOFWEEK(created_at)) DAY), "%d-%m-%Y"))';
                break;

            default:
                $dataFormat = 'DATE_FORMAT(created_at, "%d-%m-%Y")';
                break;
        }

        $select = $dataFormat . 'as create_time,
                   count(*) as total_order,
                   sum(if(status = '. Order::NEW_ORDER .', 1, 0)) as total_new,
                   sum(if(status = '. Order::DESIGN .', 1, 0)) as total_design,
                   sum(if(status = '. Order::PRODUCING .', 1, 0)) as total_producing,
                   sum(if(status = '. Order::FINISH .', 1, 0)) as total_finish,
                   sum(if(status = '. Order::CANCEL .', 1, 0)) as total_cancel';

        $model = $model->selectRaw($select);

        return $model->groupBy('create_time', 'id')->get();
    }

}
