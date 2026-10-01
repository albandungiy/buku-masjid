<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostCategories\CreateRequest;
use App\Http\Requests\PostCategories\DeleteRequest;
use App\Http\Requests\PostCategories\UpdateRequest;
use App\Models\PostCategory;

class PostCategoriesController extends Controller
{
    public function index()
    {
        $this->authorize('view-any', new PostCategory);

        $editableCategory = null;
        $categories = PostCategory::orderBy('name')->withCount('posts')->get();

        if (in_array(request('action'), ['edit', 'delete']) && request('id') != null) {
            $editableCategory = PostCategory::find(request('id'));
        }

        return view('post_categories.index', compact('categories', 'editableCategory'));
    }

    public function store(CreateRequest $postCategoryCreateForm)
    {
        $postCategoryCreateForm->save();
        flash(__('post_category.created'), 'success');

        return redirect()->route('post_categories.index');
    }

    public function update(UpdateRequest $postCategoryUpdateForm, PostCategory $post_category)
    {
        $postCategoryUpdateForm->save();
        flash(__('post_category.updated'), 'success');

        return redirect()->route('post_categories.index');
    }

    public function destroy(DeleteRequest $postCategoryDeleteForm, PostCategory $post_category)
    {
        if ($postCategoryDeleteForm->delete()) {
            flash(__('post_category.deleted'), 'warning');

            return redirect()->route('post_categories.index');
        }

        flash(__('post_category.undeleted'), 'warning');

        return back();
    }
}
