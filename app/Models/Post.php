<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    protected $fillable = ['text', 'image_path'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function repliedTo(): HasMany
    {
        return $this->hasMany(Post::class);
    }

}
