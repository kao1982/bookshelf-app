<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ReviewResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // 書籍ID
            'id' => $this->id,

            // 書籍タイトル
            'title' => $this->title,

            // 著者名
            'author' => $this->author,

            'isbn' => $this->isbn,

            // 出版日
            'published_date' => $this->published_date,

            // 概要
            'description' => $this->description,

            // 画像URL
            'image_url' => $this->image_url,

            // ジャンル情報
            'genres' => $this->genres,

            // レビュー情報
            'reviews' => ReviewResource::collection($this->whenLoaded('reviews')),

            // レビューの平均評価
            'reviews_avg_rating' => $this->reviews_avg_rating,

            // レビュー件数
            'reviews_count' => $this->reviews_count,
        ];
    }
}
