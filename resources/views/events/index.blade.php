@extends('layouts.ziswaf')

@section('title', __('event.list'))

@section('content_ziswaf')
<div class="page-header mt-4">
    <h1 class="page-title">{{ __('event.list') }}</h1>
    <div class="page-options">
        @can('create', new App\Models\Event)
            {{ link_to_route('events.create', __('event.create'), [], ['class' => 'btn btn-success']) }}
        @endcan
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        {{ Form::open(['method' => 'get', 'class' => 'form-inline']) }}
            {{ Form::select('month', get_months(), $month, ['class' => 'form-control mr-0 mr-sm-2 mb-2 mb-sm-0']) }}
            {{ Form::select('year', get_years(), $year, ['class' => 'form-control mr-0 mr-sm-2 mb-2 mb-sm-0']) }}
            {{ Form::submit(__('app.filter'), ['class' => 'btn btn-primary']) }}
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
                        <th class="text-nowrap">{{ __('event.start_date') }}</th>
                        <th class="text-nowrap">{{ __('event.title') }}</th>
                        <th class="text-nowrap">{{ __('event.location') }}</th>
                        <th class="text-nowrap">{{ __('event.book') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $key => $event)
                    <tr>
                        <td class="text-center">{{ 1 + $key }}</td>
                        <td class="text-nowrap">
                            {{ link_to_route('events.show', $event->start_date_only, [$event], ['id' => 'show-event-'.$event->id]) }}
                        </td>
                        <td class="text-nowrap">
                            <span class="badge badge-{{ $event->color_code }}">{{ $event->title }}</span>
                        </td>
                        <td class="text-nowrap">{{ $event->location }}</td>
                        <td class="text-nowrap">{{ optional($event->book)->name }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5">{{ __('event.not_found') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
