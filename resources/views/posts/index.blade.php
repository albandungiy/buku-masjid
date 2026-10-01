@extends('layouts.settings')

@section('title', __('post.list'))

@section('content_settings')
<div class="page-header mt-4">
    <h1 class="page-title">{{ __('post.list') }}</h1>
    <div class="page-subtitle">{{ __('app.total') }} : {{ $posts->total() }} {{ __('post.post') }}</div>
    <div class="page-options">
        @can('create', new App\Models\Post)
            {{ link_to_route('posts.create', __('post.create').' — '.__('post.page'), ['type_code' => 'page'], ['class' => 'btn btn-secondary mr-2']) }}
            {{ link_to_route('posts.create', __('post.create').' — '.__('post.news'), ['type_code' => 'news'], ['class' => 'btn btn-success']) }}
        @endcan
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        {{ Form::open(['method' => 'get', 'class' => 'form-inline']) }}
            {{ Form::select('type_code', [
                App\Models\Post::TYPE_PAGE => __('post.page'),
                App\Models\Post::TYPE_NEWS => __('post.news'),
            ], request('type_code'), ['placeholder' => __('post.filter_type'), 'class' => 'form-control mr-0 mr-sm-2 mb-2 mb-sm-0']) }}
            {{ Form::select('category_id', $categories, request('category_id'), ['placeholder' => __('post.filter_category'), 'class' => 'form-control mr-0 mr-sm-2 mb-2 mb-sm-0']) }}
            {{ Form::select('status_id', [
                App\Models\Post::STATUS_DRAFT => __('post.status_draft'),
                App\Models\Post::STATUS_PUBLISHED => __('post.status_published'),
            ], request('status_id'), ['placeholder' => __('post.filter_status'), 'class' => 'form-control mr-0 mr-sm-2 mb-2 mb-sm-0']) }}
            {{ Form::submit(__('app.filter'), ['class' => 'btn btn-primary mr-0 mr-sm-2']) }}
            {{ link_to_route('posts.index', __('app.reset'), [], ['class' => 'btn btn-secondary']) }}
        {{ Form::close() }}
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card table-responsive">
            <table class="table table-sm table-hover mb-0">
                <thead>
                    <tr>
                        <th class="text-center">{{ __('app.table_no') }}</th>
                        <th>{{ __('post.title') }}</th>
                        <th class="text-center">{{ __('post.type_code') }}</th>
                        <th>{{ __('post.category_id') }}</th>
                        <th class="text-center">{{ __('app.status') }}</th>
                        <th>{{ __('app.created_by') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $key => $post)
                    <tr>
                        <td class="text-center">{{ $posts->firstItem() + $key }}</td>
                        <td class="text-nowrap">
                            {{ link_to_route('posts.show', $post->title, [$post], ['id' => 'show-post-'.$post->id]) }}
                            @if ($post->isReservedPage())
                                <span class="badge badge-info">{{ __('post.reserved_badge') }}</span>
                            @endif
                        </td>
                        <td class="text-center text-nowrap">{{ $post->type_code == 'page' ? __('post.page') : __('post.news') }}</td>
                        <td class="text-nowrap">{{ optional($post->category)->name }}</td>
                        <td class="text-nowrap text-center">
                            <span class="badge {{ $post->status_id == App\Models\Post::STATUS_PUBLISHED ? 'badge-success' : 'badge-secondary' }}">
                                {{ $post->status }}
                            </span>
                        </td>
                        <td class="text-nowrap">{{ optional($post->creator)->name }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6">{{ __('post.not_found') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="card-body">{{ $posts->links() }}</div>
        </div>
    </div>
</div>
@endsection
