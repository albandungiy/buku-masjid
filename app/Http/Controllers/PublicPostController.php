<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostCategory;

class PublicPostController extends Controller
{
    public function newsIndex()
    {
        $postQuery = Post::published()->where('type_code', Post::TYPE_NEWS)->with('category');
        $postQuery->when(request('category'), function ($query, $categorySlug) {
            $query->whereHas('category', function ($query) use ($categorySlug) {
                $query->where('slug', $categorySlug);
            });
        });

        $posts = $postQuery->orderByDesc('published_at')->paginate(9)->withQueryString();
        $categories = PostCategory::orderBy('name')->get();

        return view('guest.posts.index', compact('posts', 'categories'));
    }

    public function newsShow(Post $post)
    {
        if ($post->type_code != Post::TYPE_NEWS || !$this->isPubliclyVisible($post)) {
            abort(404);
        }

        return view('guest.posts.show', compact('post'));
    }

    public function pageShow(Post $post)
    {
        if ($post->type_code != Post::TYPE_PAGE || !$this->isPubliclyVisible($post)) {
            abort(404);
        }

        return view('guest.posts.show', compact('post'));
    }

    // No draft-preview mechanism (see docs/cms.md §7, decision 5) — a draft or
    // future-scheduled post simply 404s on the public site; previewing content happens
    // through the admin posts.show page instead.
    private function isPubliclyVisible(Post $post): bool
    {
        return $post->status_id == Post::STATUS_PUBLISHED
            && (is_null($post->published_at) || $post->published_at <= now());
    }
}
