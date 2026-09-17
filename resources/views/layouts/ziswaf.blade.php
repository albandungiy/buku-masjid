@extends('layouts.app')

@section('content')
<!-- Nav tabs -->
<ul class="nav nav-tabs">
    @can('view-any', new App\Models\Donation)
        <li class="nav-item">
            {!! link_to_route('donations.index', __('donation.donation'), [], ['class' => 'nav-link'.(Request::segment(1) == 'donations' ? ' active' : '')]) !!}
        </li>
    @endcan
    @can('view-any', new App\Models\Distribution)
        <li class="nav-item">
            {!! link_to_route('distributions.index', __('distribution.distribution'), [], ['class' => 'nav-link'.(Request::segment(1) == 'distributions' ? ' active' : '')]) !!}
        </li>
    @endcan
    @can('view-any', new App\Models\Event)
        <li class="nav-item">
            {!! link_to_route('events.index', __('event.event'), [], ['class' => 'nav-link'.(Request::segment(1) == 'events' ? ' active' : '')]) !!}
        </li>
    @endcan
</ul>

@yield('content_ziswaf')
@endsection
