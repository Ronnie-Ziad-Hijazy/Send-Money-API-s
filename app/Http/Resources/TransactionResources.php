<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'transaction_type' => $this->transaction_type,
            'created_at' => $this->created_at->setTimezone('Asia/Baghdad'),
            'amount' => $this->amount,
            'status' => $this->status,
            'from' => $this->sender->email,
            'to' => $this->recipient->email,
        ];
    }
}
