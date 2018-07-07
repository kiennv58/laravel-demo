<?php

namespace App\Http\Transformers;

use App\Repositories\Orders\Order;
use League\Fractal\TransformerAbstract;

class OrderTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'creator',
        'designer',
        'producer',
        'customer',
        'order_details'
    ];

    public function transform(Order $order = null)
    {
        if (is_null($order)) return [];

        return [
            'id'            => $order->id,
            'order_code'    => $order->order_code,
            'creator_id'    => $order->creator_id,
            'designer_id'   => $order->designer_id,
            'producer_id'   => $order->producer_id,
            'creator_name'  => $order->creator ? $order->creator->name : '',
            'designer_name' => $order->designer ? $order->designer->name : '',
            'producer_name' => $order->producer ? $order->producer->name : '',
            'customer_id'   => $order->customer_id,
            'status'        => $order->status,
            'status_txt'    => $order->statusText(),
            'kpi'           => $order->kpi,
            'design_time'   => $order->design_time,
            'produce_time'  => $order->produce_time,
            'shipping_time' => $order->shipping_time,
            'deadline'      => $order->deadline,
            'total_money'   => $order->total_money,
            'created_at'    => $order->created_at ? $order->created_at->format('d-m-Y H:i:s') : '',
            'updated_at'    => $order->updated_at ? $order->updated_at->format('d-m-Y H:i:s') : '',
        ];
    }

    public function includeOrderDetails(Order $order = null)
    {
        if (is_null($order)) return $this->null();
        return $this->collection($order->order_details, new OrderDetailTransformer);
    }

    public function includeCustomer(Order $order = null)
    {
        if (is_null($order)) return $this->null();
        return $this->item($order->customer, new CustomerTransformer);
    }

    public function includeCreator(Order $order = null)
    {
        if (is_null($order)) return $this->null();
        return $this->item($order->creator, new UserTransformer);
    }

    public function includeDesigner(Order $order = null)
    {
        if (is_null($order)) return $this->null();
        return $this->item($order->designer, new UserTransformer);
    }

    public function includeProducer(Order $order = null)
    {
        if (is_null($order)) return $this->null();
        return $this->item($order->producer, new UserTransformer);
    }
}
