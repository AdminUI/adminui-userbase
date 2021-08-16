<?php

namespace AdminUI\AdminUIUserBase\Resources;

use App\Helper\LiveProduct;
use Illuminate\Http\Resources\Json\JsonResource;
use AdminUI\AdminUIAdmin\Resources\MediaResource;
use AdminUI\AdminUIEcommerce\Resources\BrandResource;
use AdminUI\AdminUIUserBase\Resources\OrderLineResource;

class OrderLineResource extends JsonResource
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
            "id"            => $this->id,
            "order_id"      => $this->order_id,
            "product_id"    => $this->product_id,
            "sku_code"      => $this->sku_code,
            "product_name"  => $this->product_name,
            "addons"        => $this->addons,
            "addon_details" => $this->addon_details,
            "priceband"     => $this->priceband,
            "item_cost"     => $this->item_cost,
            "item_exc_vat"  => $this->item_exc_vat,
            "item_inc_vat"  => $this->item_inc_vat,
            "item_vat"      => $this->item_vat,
            "item_weight"   => $this->item_weight,
            "addon_cost"    => $this->addon_cost,
            "addon_exc_vat" => $this->addon_exc_vat,
            "addon_inc_vat" => $this->addon_inc_vat,
            "addon_vat"     => $this->addon_vat,
            "addon_weight"  => $this->addon_weight,
            "qty"           => $this->qty,
            "vat_rate"      => $this->vat_rate,
            "line_cost"     => $this->line_cost,
            "line_exc_vat"  => $this->line_exc_vat,
            "line_inc_vat"  => $this->line_inc_vat,
            "line_vat"      => $this->line_vat,
            "line_weight"   => $this->line_weight,
            "created_at"    => $this->created_at,
            "updated_at"    => $this->updated_at,
            "image"         => $this->product->mediaLink(),
            "product_slug"  => $this->product->slug
        ];
    }
}
