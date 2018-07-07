<?php

namespace App\Http\Transformers;

use App\Repositories\Products\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
    ];

    public function transform(Product $product = null)
    {
        if (is_null($product)) return [];

        return [
            'id'             => $product->id,
            'type'           => $product->type,
            'type_text'      => Product::TYPE[$product->type],
            'product_code'   => $product->product_code,
            'name'           => $product->name,
            'default_price'  => $product->default_price,
            'price_per_page' => $product->price_per_page,
            'kpi'            => $product->kpi,
            'time_avg'       => $product->time_avg,
            'created_at'     => $product->created_at ? $product->created_at->format('d-m-Y H:i:s') : '',
            'updated_at'     => $product->updated_at ? $product->updated_at->format('d-m-Y H:i:s') : '',
        ];
    }
}
