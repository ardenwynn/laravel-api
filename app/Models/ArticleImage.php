<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'article_id',
        'file',
    ];
    /**
     * @return BelongsTo
     */
    public function article(): BelongsTo
    {
        $this->belongsTo(Article::class);
    }
}
