@extends('layouts.ziswaf')

@section('title', __('event.detail'))

@section('content_ziswaf')
<div class="page-header mt-4">
    <h1 class="page-title">{{ $event->title }}</h1>
    <div class="page-subtitle">{{ __('event.detail') }}</div>
    <div class="page-options">
        @can('update', $event)
            {{ link_to_route('events.edit', __('event.edit'), [$event], ['class' => 'btn btn-warning text-dark mr-2 mt-2 mt-lg-0', 'id' => 'edit-event-'.$event->id]) }}
        @endcan
        @can('delete', $event)
            {!! FormField::delete(
                ['route' => ['events.destroy', $event], 'onsubmit' => __('event.delete_confirm')],
                __('app.delete'),
                ['class' => 'btn btn-danger mr-2 mt-2 mt-lg-0', 'id' => 'delete-event-'.$event->id],
                ['event_id' => $event->id]
            ) !!}
        @endcan
        {{ link_to_route('events.index', __('event.back_to_index'), [], ['class' => 'btn btn-secondary mt-2 mt-lg-0']) }}
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card card-default">
            <table class="table card-table table-sm">
                <tbody>
                    <tr><td class="col-5">{{ __('event.title') }}</td><td>{{ $event->title }}</td></tr>
                    <tr><td>{{ __('event.start_date') }}</td><td>{{ $event->start_date }}</td></tr>
                    @if ($event->end_date)
                        <tr><td>{{ __('event.end_date') }}</td><td>{{ $event->end_date }}</td></tr>
                    @endif
                    <tr><td>{{ __('event.location') }}</td><td>{{ $event->location }}</td></tr>
                    <tr><td>{{ __('event.book') }}</td><td>{{ optional($event->book)->name ?? __('event.book_none') }}</td></tr>
                    <tr><td>{{ __('app.description') }}</td><td>{!! nl2br(htmlentities($event->description ?? '')) !!}</td></tr>
                    <tr><td>{{ __('app.created_by') }}</td><td>{{ $event->creator->name }}</td></tr>
                    <tr><td>{{ __('app.created_at') }}</td><td>{{ $event->created_at }}</td></tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    {{ __('event.documentation') }}
                    @if (!$event->files->isEmpty())
                        ({{ $event->files->count() }})
                    @endif
                </h3>
            </div>
        </div>
        <div class="row">
            @forelse ($event->files as $file)
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            @if (in_array($file->type_code, ['raw_image', 'image']))
                                <div class="mb-2 text-center">
                                    <a href="{{ asset('storage/'.$file->file_path) }}">
                                        <img src="{{ asset('storage/'.$file->file_path) }}" alt="{{ __('event.documentation') }}" class="img-fluid">
                                    </a>
                                </div>
                            @else
                                <a href="{{ asset('storage/'.$file->file_path) }}" target="_blank"><i class="fe fe-file"></i> {{ __('event.documentation') }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-muted p-3">{{ __('event.documentation') }}: -</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
