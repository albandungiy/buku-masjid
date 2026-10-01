<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class RunningText extends Model
{
    protected $fillable = ['content', 'is_active', 'order', 'creator_id'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('order');
    }
}
