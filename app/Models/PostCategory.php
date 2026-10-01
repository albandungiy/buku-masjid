<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class PostCategory extends Model
{
    protected $fillable = ['name', 'slug', 'creator_id'];

    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'category_id');
    }
}
