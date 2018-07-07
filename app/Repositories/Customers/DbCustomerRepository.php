<?php

namespace App\Repositories\Customers;
use App\Repositories\BaseRepository;

class DbCustomerRepository extends BaseRepository implements CustomerRepository
{
    public function __construct(Customer $customer)
    {
        $this->model = $customer;
    }

    public function getOrCreateCustomer ($params) {
        $email = array_get($params, 'email');
        $name = array_get($params, 'name');
        $phone = array_get($params, 'phone');
        $address = array_get($params, 'address');

        $model = $this->model;

        if ($email && $phone) {
            $modelCheck = $model;
            $modelCheck = $modelCheck->where('phone', $phone);
            if (!$modelCheck->first()) {
                $model= $model->where('email', $email);
            } else {
                $model = $modelCheck;
            }
        } else if ($phone) {
            $model = $model->where('phone', $phone);
        } else if ($email) {
            $model = $model->where('email', $email);
        }

        $customer = $model->first();
        if (!$customer) {
            $customer = $this->store([
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'address' => $address
            ]);
        } else {
            $customer->name = $name;
            $customer->save();
        }
        return $customer;
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
        $query       = array_get($params, 'q', '');

        $model = $this->model;

        if (!empty($sorting)) {
            $model = $model->orderBy($sorting[0], $sorting[1] > 0 ? 'ASC' : 'DESC');
        }

        if ($query != '') {
            $model = $model->where(function($q) use ($query) {
                return $q->where('name', 'like', "%{$query}%")
                            ->orWhere('phone', 'like', "%{$query}%")
                            ->orWhere('email', 'like', "%{$query}%");
            });
        }

        return $size < 0 ? $model->get() : $model->paginate($size);
    }
}
