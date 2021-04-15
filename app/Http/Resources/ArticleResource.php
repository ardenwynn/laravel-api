<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->when(isset($this->id), $this->id),
            'user_id' => $this->when(isset($this->user_id), $this->user_id),
            'title' => $this->when(isset($this->title), $this->title),
            'content' => $this->when(isset($this->content), $this->content),
            'user' => $this->whenLoaded('user'),
            'comments' => $this->whenLoaded('comments'),
        ];
    }
}
