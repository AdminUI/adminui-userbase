<?php

namespace AdminUI\AdminUIUserBase\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Helper\LiveProduct;

class ProductResourceWishList extends JsonResource
{
    /**
     * Resources customizable for the ecommerce package
     *
     * @param mixed $request
     *
     * @return [array]
     */
    public function toArray($request)
    {
        // Check if the productd is in on the the wishlist
        // $wishListed = false;
        // if (Auth()->check()) {
        //     $wishListed  = empty(Auth()->user()->wishlist->where('product_id', $this->id)->first()) ? false : true;
        // }

        return [
            "id"            => $this->id,
            "name"          => $this->name,
            "slug"          => $this->slug,
            "sku_code"      => $this->sku_code,
            // "is_wishlisted" => $wishListed,
            "link"       => route('view.product', $this->slug),
            "live_data"  => LiveProduct::getLiveData($this),
            "media"      => $this->mediaLink(),
            "categories" => $this->category(),
            //'media'         => MediaResource::collection($this->media),
            'brand'         => [
                'name' => $this->brand->name,
                'slug' => $this->brand->slug,
            ],
            'description'   => [
                'short_description' => $this->description->short_description
            ]
        ];
    }
}
