<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;

class Menu extends Model
{
    const TARGET_ROUTE = 'route';
    const TARGET_POST = 'post';
    const TARGET_URL = 'url';

    protected $fillable = [
        'location_code', 'parent_id', 'label', 'target_type', 'target_value', 'order', 'is_active', 'creator_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')->where('is_active', true)->orderBy('order');
    }

    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Resolve this menu item's link based on target_type — see docs/cms.md §3.3.
     * Returns null (rather than throwing) whenever the target no longer resolves — a
     * route behind a disabled feature flag, or a deleted/unpublished Post — so rendering
     * can simply skip the item instead of showing a broken link.
     */
    public function getUrlAttribute(): ?string
    {
        switch ($this->target_type) {
            case self::TARGET_ROUTE:
                return Route::has($this->target_value) ? route($this->target_value) : null;
            case self::TARGET_POST:
                $post = Post::find($this->target_value);

                return $post ? $post->url : null;
            case self::TARGET_URL:
                return $this->target_value;
            default:
                return null;
        }
    }
}
