<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->when(isset($this->id), $this->id),
            'user_id' => $this->when(isset($this->user_id), $this->user_id),
            'article_id' => $this->when(isset($this->article_id), $this->article_id),
            'content' => $this->when(isset($this->content), $this->content),
            'user' => $this->whenLoaded('user'),
            'article' => $this->whenLoaded('article'),
        ];
    }
}
