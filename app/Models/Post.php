<?php

namespace App\Models;

use App\Traits\Models\ConstantsGetter;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;

class Post extends Model
{
    use ConstantsGetter;

    const TYPE_PAGE = 'page';
    const TYPE_NEWS = 'news';

    const STATUS_DRAFT = 0;
    const STATUS_PUBLISHED = 1;

    protected $fillable = [
        'type_code', 'category_id', 'title', 'slug', 'excerpt', 'content', 'status_id',
        'published_at', 'meta_title', 'meta_description', 'creator_id',
    ];

    protected $appends = ['status'];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(PostCategory::class);
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    /**
     * Published & not scheduled for the future.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status_id', self::STATUS_PUBLISHED)
            ->where(function (Builder $query) {
                $query->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }

    public function getStatusAttribute(): string
    {
        return $this->status_id == self::STATUS_PUBLISHED ? __('post.status_published') : __('post.status_draft');
    }

    /**
     * One of the fixed homepage-section Pages (see config('cms.reserved_page_slugs') and
     * docs/cms.md §5.2) — its slug can't be changed and it can't be deleted, enforced in
     * PostPolicy and Posts\UpdateRequest, so the homepage sections can never go missing.
     */
    public function isReservedPage(): bool
    {
        return $this->type_code == self::TYPE_PAGE
            && array_key_exists($this->slug, config('cms.reserved_page_slugs', []));
    }

    /**
     * Public URL for this post. Uses Route::has() as a guard rather than assuming the
     * Fase 4 public routes already exist — mirrors Menu::getUrlAttribute()'s pattern of
     * degrading to null (skip) instead of throwing when a route isn't registered.
     */
    public function getUrlAttribute(): ?string
    {
        $routeName = $this->type_code == self::TYPE_PAGE ? 'public.pages.show' : 'public.news.show';

        return Route::has($routeName) ? route($routeName, $this->slug) : null;
    }
}
