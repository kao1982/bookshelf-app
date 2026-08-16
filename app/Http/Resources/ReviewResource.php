<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            // レビューID
            'id' => $this->id,

            // 投稿者名
            'user_name' => $this->user->name,

            // 評価
            'rating' => $this->rating,

            // コメント
            'comment' => $this->comment,

            // 投稿日時
            'created_at' => $this->created_at,
        ];
    }
}
