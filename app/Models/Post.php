<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Post extends Model
{
    protected $fillable = ['text', 'image_path', 'post_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tag(string $name): void
    {
        $tag = Tag::firstOrCreate(['name' => strtolower($name)]);

        $this->tags()->attach($tag);
    }


    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function repliedTo(): HasOne
    {
        return $this->hasOne(Post::class);
    }

}
