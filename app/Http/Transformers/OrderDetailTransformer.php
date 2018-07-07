<?php

namespace App\Http\Transformers;

use App\Repositories\OrderDetails\OrderDetail;
use League\Fractal\TransformerAbstract;
use Carbon\Carbon;

class OrderDetailTransformer extends TransformerAbstract
{
    protected $availableIncludes = [

    ];

    public function transform(OrderDetail $order_detail = null)
    {
        if (is_null($order_detail)) {
            return [];
        }

        return [
            'id'             => $order_detail->id,
            'order_id'       => $order_detail->order_id,
            'product_id'     => $order_detail->product_id,
            'product_name'   => $order_detail->product->name,
            'size'           => $order_detail->size,
            'quantity'       => $order_detail->quantity,
            'page_id'        => $order_detail->page_id,
            'page_cover_id'  => $order_detail->page_cover_id,
            'tape'           => $order_detail->tape,
            'can'            => $order_detail->can,
            'spine'          => $order_detail->spine,
            'glue'           => $order_detail->glue,
            'color'          => $order_detail->color,
            'cover_color'    => $order_detail->cover_color,
            'plastic'        => $order_detail->plastic,
            'odd_page'       => $order_detail->odd_page,
            'hole'           => $order_detail->hole,
            'size_file'      => $order_detail->size_file,
            'price'          => $order_detail->price,
            'created_at'     => $order_detail->created_at ? $order_detail->created_at->format('d-m-Y H:i:s') : '',
            'updated_at'     => $order_detail->updated_at ? $order_detail->updated_at->format('d-m-Y H:i:s') : ''
        ];
    }
}
