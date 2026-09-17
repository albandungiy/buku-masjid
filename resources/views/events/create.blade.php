@extends('layouts.ziswaf')

@section('title', __('event.create'))

@section('content_ziswaf')
<div class="row justify-content-center mt-4">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">{{ __('event.create') }}</div>
            {!! Form::open(['route' => 'events.store', 'autocomplete' => 'off', 'files' => true]) !!}
            <div class="card-body">
                {!! FormField::text('title', [
                    'required' => true,
                    'label' => __('event.title'),
                    'value' => old('title'),
                ]) !!}
                <div class="row">
                    <div class="col-md-6">
                        {!! FormField::text('start_date', [
                            'required' => true,
                            'label' => __('event.start_date'),
                            'value' => old('start_date', now()->format('Y-m-d H:i')),
                            'class' => 'datetime-select',
                        ]) !!}
                    </div>
                    <div class="col-md-6">
                        {!! FormField::text('end_date', [
                            'label' => __('event.end_date'),
                            'value' => old('end_date'),
                            'class' => 'datetime-select',
                        ]) !!}
                    </div>
                </div>
                {!! FormField::text('location', [
                    'label' => __('event.location'),
                    'value' => old('location'),
                ]) !!}
                {!! FormField::select('book_id', $books, [
                    'label' => __('event.book'),
                    'placeholder' => __('event.book_none'),
                    'value' => old('book_id'),
                ]) !!}
                {!! FormField::select('color_code', [
                    'primary' => __('event.color_primary'), 'success' => __('event.color_success'),
                    'warning' => __('event.color_warning'), 'danger' => __('event.color_danger'), 'info' => __('event.color_info'),
                ], [
                    'label' => __('event.color'),
                    'value' => old('color_code', 'primary'),
                ]) !!}
                {!! FormField::textarea('description', [
                    'label' => __('event.description'),
                    'value' => old('description'),
                ]) !!}
                <div class="form-group {{ $errors->has('files.*') ? 'has-error' : '' }}">
                    <label for="files" class="form-label fw-bold">{{ __('event.upload_documentation') }}</label>
                    {{ Form::file('files[]', ['multiple' => true, 'class' => 'form-control-file border p-2 rounded '.($errors->has('files.*') ? 'is-invalid' : '')]) }}
                    @if ($errors->has('files.*'))
                        @foreach ($errors->get('files.*') as $key => $errorMessages)
                            {!! $errors->first($key, '<span class="invalid-feedback" role="alert">:message</span>') !!}
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="card-footer">
                {!! Form::submit(__('event.create'), ['class' => 'btn btn-success']) !!}
                {{ link_to_route('events.index', __('app.cancel'), [], ['class' => 'btn btn-link']) }}
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
