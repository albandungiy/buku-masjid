@extends('layouts.guest')

@section('title', __('app.welcome'))

@section('content')

{{-- Note: deliberately not using the shared .section-hero class here — its 120px
     bottom padding exists so other public pages' single hero can visually overlap the
     content right after it. This page stacks several sections below the hero instead,
     so that padding would just stack with each section's own spacing. --}}
<section class="bg-white">
    <div class="container-md py-4">
        @if (config('features.shalat_time.is_active'))
            @include('guest._welcome_shalat_time_matrix')
        @endif
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="card shadow-sm p-4">
                    @include('layouts.public._masjid_info')
                </div>
            </div>
            <div class="d-none d-lg-block col-lg-6 position-relative">
                @if (Setting::get('masjid_photo_path'))
                    <img src="{{ Storage::url(Setting::get('masjid_photo_path'))}}" class="rounded">
                @else
                    <div style="background-color: #f8f8f8; height: 360px"></div>
                @endif
                <img src="{{ asset('images/image_cover.svg') }}" class="position-absolute top-0 start-0">
            </div>
        </div>
    </div>
</section>

@php
    // Reserved homepage-section Pages (docs/cms.md §5.2) — rendered inline by fixed
    // slug, not via the Menu system. Gracefully absent (not an error) on a fresh
    // install before HomepageContentSeeder has run.
    $sejarahPost = \App\Models\Post::published()->where('slug', 'sejarah')->first();
    $visiMisiPost = \App\Models\Post::published()->where('slug', 'visi-misi')->first();
    $strukturPengurusPost = \App\Models\Post::published()->where('slug', 'struktur-pengurus')->first();
@endphp

@if ($sejarahPost || $visiMisiPost || $strukturPengurusPost)
<section class="bg-white">
    <div class="container-md py-4">
        @if ($sejarahPost)
            <div class="row mb-5">
                <div class="col-md-10 mx-auto">
                    <h2 class="mb-3">{{ $sejarahPost->title }}</h2>
                    <div>{!! $sejarahPost->content !!}</div>
                </div>
            </div>
        @endif
        @if ($visiMisiPost)
            <div class="row mb-5">
                <div class="col-md-10 mx-auto">
                    <h2 class="mb-3">{{ $visiMisiPost->title }}</h2>
                    <div>{!! $visiMisiPost->content !!}</div>
                </div>
            </div>
        @endif
        @if ($strukturPengurusPost)
            <div class="row">
                <div class="col-md-10 mx-auto">
                    <h2 class="mb-3">{{ $strukturPengurusPost->title }}</h2>
                    <div>{!! $strukturPengurusPost->content !!}</div>
                </div>
            </div>
        @endif
    </div>
</section>
@endif

<section class="border-top" style="background-color: #f8f8f8">
    <div class="container-md py-5">
        <div class="row align-items-start">
            <div class="col-lg-6 mb-4 mb-lg-0">
                @livewire('public-home.book-cards')
            </div>
            <div class="col-lg-6">
                @if (Route::has('lecturings.index'))
                    @livewire('public-home.daily-lecturings', ['date' => today(), 'dayTitle' => 'today'])
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
