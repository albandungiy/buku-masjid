<?php

namespace App\Http\Controllers;

use App\Http\Requests\Posts\CreateRequest;
use App\Http\Requests\Posts\UpdateRequest;
use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view-any', new Post);

        $postQuery = Post::query()->with(['category', 'creator']);
        $postQuery->when($request->get('type_code'), function ($query, $typeCode) {
            $query->where('type_code', $typeCode);
        });
        $postQuery->when($request->get('category_id'), function ($query, $categoryId) {
            $query->where('category_id', $categoryId);
        });
        $postQuery->when($request->get('status_id') !== null && $request->get('status_id') !== '', function ($query) use ($request) {
            $query->where('status_id', $request->get('status_id'));
        });

        $posts = $postQuery->orderByDesc('created_at')->paginate(20)->withQueryString();
        $categories = PostCategory::orderBy('name')->pluck('name', 'id');

        return view('posts.index', compact('posts', 'categories'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', new Post);

        $typeCode = $request->get('type_code') == Post::TYPE_PAGE ? Post::TYPE_PAGE : Post::TYPE_NEWS;
        $categories = PostCategory::orderBy('name')->pluck('name', 'id');

        return view('posts.create', compact('typeCode', 'categories'));
    }

    public function store(CreateRequest $postCreateForm)
    {
        $post = $postCreateForm->save();
        flash(__('post.created'), 'success');

        return redirect()->route('posts.show', $post);
    }

    public function show(Post $post)
    {
        $this->authorize('view', $post);

        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);

        $categories = PostCategory::orderBy('name')->pluck('name', 'id');

        return view('posts.edit', compact('post', 'categories'));
    }

    public function update(UpdateRequest $postUpdateForm, Post $post)
    {
        $post = $postUpdateForm->save();
        flash(__('post.updated'), 'success');

        return redirect()->route('posts.show', $post);
    }

    public function destroy(Request $request, Post $post)
    {
        $this->authorize('delete', $post);

        $request->validate(['post_id' => 'required']);

        if ($request->get('post_id') == $post->id) {
            $post->files->each->delete();
            if ($post->delete()) {
                flash(__('post.deleted'), 'success');

                return redirect()->route('posts.index');
            }
        }

        flash(__('post.undeleted'), 'error');

        return back();
    }
}
