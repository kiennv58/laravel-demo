<?php

namespace App\Http\Transformers;

use App\Repositories\Customers\Customer;
use League\Fractal\TransformerAbstract;
use Carbon\Carbon;

class CustomerTransformer extends TransformerAbstract
{
    protected $availableIncludes = [

    ];

    public function transform(Customer $customer = null)
    {
        if (is_null($customer)) {
            return [];
        }

        return [
            'id'         => $customer->id,
            'name'       => $customer->name,
            'phone'      => $customer->phone,
            'email'      => $customer->email,
            'address'    => $customer->address,
            'created_at' => $customer->created_at ? $customer->created_at->format('d-m-Y H:i:s') : '',
            'updated_at' => $customer->updated_at ? $customer->updated_at->format('d-m-Y H:i:s') : ''
        ];
    }
}
