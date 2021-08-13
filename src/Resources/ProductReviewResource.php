<?php

namespace AdminUI\AdminUIUserBase\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductReviewResource extends JsonResource
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
        return [
            "id"         => $this->id,
            "comment"    => $this->comment,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "rate"       => $this->rate,
            "product"    => new ProductResource($this->product),
        ];
    }
}
