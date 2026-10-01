@extends('layouts.guest')

@section('title', __('post.news'))

@section('content')
<section class="bg-white">
    <div class="container-md py-4">
        <h1 class="mb-4">{{ __('post.news') }}</h1>

        @if ($categories->isNotEmpty())
            <div class="mb-4">
                <a href="{{ route('public.news.index') }}" class="btn btn-sm {{ !request('category') ? 'btn-primary' : 'btn-outline-secondary' }} mr-1 mb-1">{{ __('post.filter_category') }}</a>
                @foreach ($categories as $category)
                    <a href="{{ route('public.news.index', ['category' => $category->slug]) }}" class="btn btn-sm {{ request('category') == $category->slug ? 'btn-primary' : 'btn-outline-secondary' }} mr-1 mb-1">{{ $category->name }}</a>
                @endforeach
            </div>
        @endif

        <div class="row">
            @forelse ($posts as $post)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        @if ($post->files->isNotEmpty())
                            <img src="{{ asset('storage/'.$post->files->first()->file_path) }}" class="card-img-top" style="height:180px;object-fit:cover">
                        @elseif (Setting::get('masjid_logo_path'))
                            <img src="{{ Storage::url(Setting::get('masjid_logo_path')) }}" class="card-img-top" style="height:180px;object-fit:contain;background-color:#f8f8f8;padding:20px">
                        @endif
                        <div class="card-body d-flex flex-column">
                            @if ($post->category)
                                <span class="badge badge-info mb-2 align-self-start">{{ $post->category->name }}</span>
                            @endif
                            <h5 class="card-title">
                                <a href="{{ route('public.news.show', $post->slug) }}">{{ $post->title }}</a>
                            </h5>
                            <p class="card-text text-muted small">{{ optional($post->published_at)->isoFormat('D MMMM Y') }}</p>
                            <p class="card-text">{{ Illuminate\Support\Str::limit($post->excerpt ?: strip_tags($post->content), 120) }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-muted">{{ __('post.not_found') }}</div>
            @endforelse
        </div>

        {{ $posts->links() }}
    </div>
</section>
@endsection
