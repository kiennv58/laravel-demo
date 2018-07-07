<?php

namespace App\Http\Transformers;

use App\Repositories\Assets\Asset;
use League\Fractal\TransformerAbstract;
use Carbon\Carbon;

class AssetTransformer extends TransformerAbstract
{
    protected $availableIncludes = [

    ];

    public function transform(Asset $asset = null)
    {
        if (is_null($asset)) {
            return [];
        }

        return [
            'id'            => $asset->id,
            'type'          => $asset->type,
            'code'          => $asset->code,
            'name'          => $asset->name,
            'description'   => $asset->description,
            'default_price' => $asset->default_price,
            'import_price'  => $asset->import_price,
            'quantity'      => $asset->quantity,
            'created_at'    => $asset->created_at ? $asset->created_at->format('d-m-Y H:i:s') : '',
            'updated_at'    => $asset->updated_at ? $asset->updated_at->format('d-m-Y H:i:s') : ''
        ];
    }
}
