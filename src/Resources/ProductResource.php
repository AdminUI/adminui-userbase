<?php

namespace AdminUI\AdminUIUserBase\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use AdminUI\AdminUIAdmin\Resources\MediaResource;
use AdminUI\AdminUIEcommerce\Resources\BrandResource;
use App\Helper\LiveProduct;

class ProductResource extends JsonResource
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
        $wishListed = false;
        if (Auth()->check()) {
            $wishListed  = empty(Auth()->user()->wishlist->where('product_id', $this->id)->first()) ? false : true;
        }

        return [
            "id"            => $this->id,
            "name"          => $this->name,
            "slug"          => $this->slug,
            "sku_code"      => $this->sku_code,
            "media"         => $this->mediaLink(),
            "is_wishlisted" => $wishListed,
            "link"          => route('view.product', $this->slug),
            'media'         => MediaResource::collection($this->media),
            'brand'         => new BrandResource($this->brand),
            'description'   => $this->description
        ];
    }
}
