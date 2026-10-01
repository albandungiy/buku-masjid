@extends('layouts.guest')

@section('title', $post->meta_title ?: $post->title)

@section('head_tags')
    @if ($post->meta_description)
        <meta name="description" content="{{ $post->meta_description }}">
    @endif
@endsection

@section('content')
<section class="bg-white">
    <div class="container-md py-4" style="max-width: 800px">
        @if ($post->category)
            <span class="badge badge-info mb-2">{{ $post->category->name }}</span>
        @endif
        <h1>{{ $post->title }}</h1>
        @if ($post->type_code == App\Models\Post::TYPE_NEWS)
            <p class="text-muted">{{ optional($post->published_at)->isoFormat('dddd, D MMMM Y') }}</p>
        @endif
        @if ($post->files->isNotEmpty())
            <img src="{{ asset('storage/'.$post->files->first()->file_path) }}" class="img-fluid rounded mb-4">
        @endif
        <div class="post-content">
            {!! $post->content !!}
        </div>

        @if ($post->type_code == App\Models\Post::TYPE_NEWS)
            <div class="mt-4">
                <a href="{{ route('public.news.index') }}" class="btn btn-secondary">{{ __('app.back') }}</a>
            </div>
        @endif
    </div>
</section>
@endsection
