@extends('layouts.ziswaf')

@section('title', __('event.edit'))

@section('content_ziswaf')
<div class="row justify-content-center mt-4">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">{{ __('event.edit') }}</div>
            {!! Form::model($event, ['route' => ['events.update', $event], 'method' => 'put', 'autocomplete' => 'off']) !!}
            <div class="card-body">
                {!! FormField::text('title', [
                    'required' => true,
                    'label' => __('event.title'),
                    'value' => old('title', $event->title),
                ]) !!}
                <div class="row">
                    <div class="col-md-6">
                        {!! FormField::text('start_date', [
                            'required' => true,
                            'label' => __('event.start_date'),
                            'value' => old('start_date', $event->start_date),
                            'class' => 'datetime-select',
                        ]) !!}
                    </div>
                    <div class="col-md-6">
                        {!! FormField::text('end_date', [
                            'label' => __('event.end_date'),
                            'value' => old('end_date', $event->end_date),
                            'class' => 'datetime-select',
                        ]) !!}
                    </div>
                </div>
                {!! FormField::text('location', [
                    'label' => __('event.location'),
                    'value' => old('location', $event->location),
                ]) !!}
                {!! FormField::select('book_id', $books, [
                    'label' => __('event.book'),
                    'placeholder' => __('event.book_none'),
                    'value' => old('book_id', $event->book_id),
                ]) !!}
                {!! FormField::select('color_code', [
                    'primary' => __('event.color_primary'), 'success' => __('event.color_success'),
                    'warning' => __('event.color_warning'), 'danger' => __('event.color_danger'), 'info' => __('event.color_info'),
                ], [
                    'label' => __('event.color'),
                    'value' => old('color_code', $event->color_code),
                ]) !!}
                {!! FormField::textarea('description', [
                    'label' => __('event.description'),
                    'value' => old('description', $event->description),
                ]) !!}
            </div>
            <div class="card-footer">
                {!! Form::submit(__('app.update'), ['class' => 'btn btn-success']) !!}
                {{ link_to_route('events.show', __('app.cancel'), [$event], ['class' => 'btn btn-link']) }}
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection

@section('styles')
    {{ Html::style(url('css/plugins/jquery.datetimepicker.css')) }}
@endsection

@push('scripts')
    {{ Html::script(url('js/plugins/jquery.datetimepicker.js')) }}
<script>
(function () {
    $('.datetime-select').datetimepicker({
        format: 'Y-m-d H:i',
        closeOnDateSelect: true,
        dayOfWeekStart: 1,
    });
})();
</script>
@endpush
