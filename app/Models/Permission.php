<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Permission extends Model
{
    protected $fillable = ['name'];
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
    public function roles(): belongsToMany
    {
        return $this->belongsToMany(Role::class);
    }
}
