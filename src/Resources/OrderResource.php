<?php

namespace AdminUI\AdminUIUserBase\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use AdminUI\AdminUIUserBase\Resources\OrderLineResource;

class OrderResource extends JsonResource
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
            "id"                   => $this->id,
            "uuid"                 => $this->uuid,
            "user_id"              => $this->user_id,
            "billing_id"           => $this->billing_id,
            "delivery_id"          => $this->delivery_id,
            "vat_rate"             => $this->vat_rate,
            "discount_exc_vat"     => $this->discount_exc_vat,
            "discount_inc_vat"     => $this->discount_inc_vat,
            "discount_vat"         => $this->discount_vat,
            "discount_description" => $this->discount_description,
            "discount_code"        => $this->discount_code,
            "postage_cost"         => $this->postage_cost,
            "postage_exc_vat"      => $this->postage_exc_vat,
            "postage_inc_vat"      => $this->postage_inc_vat,
            "postage_vat"          => $this->postage_vat,
            "postage_description"  => $this->postage_description,
            "postage_code"         => $this->postage_code,
            "subtotal_cost"        => $this->subtotal_cost,
            "subtotal_exc_vat"     => $this->subtotal_exc_vat,
            "subtotal_inc_vat"     => $this->subtotal_inc_vat,
            "subtotal_vat"         => $this->subtotal_vat,
            "subtotal_weight"      => $this->subtotal_weight,
            "cart_cost"            => $this->cart_cost,
            "cart_inc_vat"         => $this->cart_inc_vat,
            "cart_exc_vat"         => $this->cart_exc_vat,
            "cart_vat"             => $this->cart_vat,
            "cart_fees"            => $this->cart_fees,
            "message"              => $this->message,
            "instruction"          => $this->instruction,
            "orderRef"             => $this->orderRef,
            "delivery_method"      => $this->delivery_method,
            "delivery_company"     => $this->delivery_company,
            "tracking_code"        => $this->tracking_code,
            "status"               => $this->status,
            "admin_notes"          => $this->admin_notes,
            "paid_at"              => $this->paid_at,
            "sent_at"              => $this->sent_at,
            "order_status_id"      => $this->order_status_id,
            "order_status"         => optional($this->orderStatus)->name,
            "payment_type"         => $this->payment_type,
            "payment_code"         => $this->payment_code,
            "created_at"           => $this->created_at,
            "updated_at"           => $this->updated_at,
            "order_items"          => OrderLineResource::collection($this->lines),
        ];
    }
}
