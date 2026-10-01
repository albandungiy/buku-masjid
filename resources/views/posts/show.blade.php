@extends('layouts.settings')

@section('title', $post->title)

@section('content_settings')
<div class="page-header mt-4">
    <h1 class="page-title">{{ $post->title }}</h1>
    <div class="page-subtitle">{{ __('post.detail') }}</div>
    <div class="page-options">
        @if ($post->url)
            <a href="{{ $post->url }}" target="_blank" class="btn btn-secondary mr-2 mt-2 mt-lg-0">{{ __('app.show') }}</a>
        @endif
        @can('update', $post)
            {{ link_to_route('posts.edit', __('post.edit'), [$post], ['class' => 'btn btn-warning text-dark mr-2 mt-2 mt-lg-0', 'id' => 'edit-post-'.$post->id]) }}
        @endcan
        @can('delete', $post)
            {!! FormField::delete(
                ['route' => ['posts.destroy', $post], 'onsubmit' => __('post.delete_confirm')],
                __('app.delete'),
                ['class' => 'btn btn-danger mr-2 mt-2 mt-lg-0', 'id' => 'delete-post-'.$post->id],
                ['post_id' => $post->id]
            ) !!}
        @endcan
        {{ link_to_route('posts.index', __('post.back_to_index'), [], ['class' => 'btn btn-secondary mt-2 mt-lg-0']) }}
    </div>
</div>

@if ($post->isReservedPage())
    <div class="alert alert-info">{{ __('post.reserved_notice') }}</div>
@endif

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-default">
            <table class="table card-table table-sm">
                <tbody>
                    <tr><td class="col-3">{{ __('post.type_code') }}</td><td>{{ $post->type_code == 'page' ? __('post.page') : __('post.news') }}</td></tr>
                    @if ($post->category)
                        <tr><td>{{ __('post.category_id') }}</td><td>{{ $post->category->name }}</td></tr>
                    @endif
                    <tr><td>{{ __('post.slug') }}</td><td><code>{{ $post->slug }}</code></td></tr>
                    <tr>
                        <td>{{ __('app.status') }}</td>
                        <td>
                            <span class="badge {{ $post->status_id == App\Models\Post::STATUS_PUBLISHED ? 'badge-success' : 'badge-secondary' }}">
                                {{ $post->status }}
                            </span>
                        </td>
                    </tr>
                    @if ($post->published_at)
                        <tr><td>{{ __('post.published_at') }}</td><td>{{ $post->published_at }}</td></tr>
                    @endif
                    <tr><td>{{ __('app.created_by') }}</td><td>{{ optional($post->creator)->name }}</td></tr>
                    <tr><td>{{ __('app.created_at') }}</td><td>{{ $post->created_at }}</td></tr>
                </tbody>
            </table>
            <div class="card-body">
                <h5 class="text-muted">{{ __('post.content') }}</h5>
                {!! $post->content !!}
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    {{ __('post.featured_image') }}
                    @if (!$post->files->isEmpty())
                        ({{ $post->files->count() }})
                    @endif
                </h3>
            </div>
        </div>
        <div class="row">
            @forelse ($post->files as $file)
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            @if (in_array($file->type_code, ['raw_image', 'image']))
                                <a href="{{ asset('storage/'.$file->file_path) }}">
                                    <img src="{{ asset('storage/'.$file->file_path) }}" class="img-fluid">
                                </a>
                            @else
                                <a href="{{ asset('storage/'.$file->file_path) }}" target="_blank"><i class="fe fe-file"></i> {{ __('post.featured_image') }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-muted p-3">-</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
