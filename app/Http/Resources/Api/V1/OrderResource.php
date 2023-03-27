<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "uuid" => $this->uuid,
            "order_status" => $this->orderStatus->title,
            "user_id" => $this->user_id,
            "payment" => $this->payment_id ? $this->payment->type : $this->payment_id,
            "products" => $this->products($this->products),
            "address" => $this->address,
            "delivery_fee" => $this->delivery_fee,
            "amount" => $this->amount,
            "created_by" => $this->user->first_name,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "shipped_at" => $this->shipped_at,
        ];
    }
}
