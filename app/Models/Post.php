<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Post extends Model
{
    protected $fillable = ['text', 'image_path', 'post_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function repliedTo(): HasOne
    {
        return $this->hasOne(Post::class);
    }

}
