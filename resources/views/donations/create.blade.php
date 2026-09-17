@extends('layouts.ziswaf')

@section('title', __('donation.create'))

@section('content_ziswaf')
<div class="row justify-content-center mt-4">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">{{ __('donation.create') }}</div>
            {!! Form::open(['route' => 'donations.store', 'autocomplete' => 'off', 'files' => true]) !!}
            <div class="card-body">
                {!! FormField::select('book_id', $fundBooks, [
                    'required' => true,
                    'label' => __('donation.fund_book'),
                    'value' => old('book_id'),
                ]) !!}
                {!! FormField::select('partner_id', $muzakkiPartners, [
                    'label' => __('donation.muzakki'),
                    'placeholder' => __('donation.anonymous'),
                    'value' => old('partner_id'),
                ]) !!}
                <div class="row">
                    <div class="col-md-6">
                        {!! FormField::text('date', [
                            'required' => true,
                            'label' => __('donation.date'),
                            'value' => old('date', now()->format('Y-m-d')),
                            'class' => 'date-select',
                        ]) !!}
                    </div>
                    <div class="col-md-6">
                        {!! FormField::price('amount', [
                            'required' => true,
                            'label' => __('donation.amount'),
                            'addon' => ['before' => config('money.currency_code')],
                            'step' => number_step(),
                            'value' => old('amount'),
                        ]) !!}
                    </div>
                </div>
                {!! FormField::radios('payment_method_code', $paymentMethods, [
                    'required' => true,
                    'label' => __('donation.payment_method'),
                    'value' => old('payment_method_code'),
                ]) !!}
                <div class="form-group {{ $errors->has('files.*') ? 'has-error' : '' }}">
                    <label for="files" class="form-label fw-bold">{{ __('donation.upload_proof') }}</label>
                    @if($isDiskFull)
                        <div class="alert alert-warning my-2 p-2" role="alert">{{ __('transaction.disk_is_full') }}</div>
                    @else
                        {{ Form::file('files[]', ['multiple' => true, 'class' => 'form-control-file border p-2 rounded '.($errors->has('files.*') ? 'is-invalid' : ''), 'accept' => 'image/*']) }}
                        @if ($errors->has('files.*'))
                            @foreach ($errors->get('files.*') as $key => $errorMessages)
                                {!! $errors->first($key, '<span class="invalid-feedback" role="alert">:message</span>') !!}
                            @endforeach
                        @endif
                    @endif
                </div>
            </div>
            <div class="card-footer">
                {!! Form::submit(__('donation.create'), ['class' => 'btn btn-success']) !!}
                {{ link_to_route('donations.index', __('app.cancel'), [], ['class' => 'btn btn-link']) }}
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
    {{ Html::script(url('js/plugins/number-format.js')) }}
<script>
(function () {
    $('.date-select').datetimepicker({
        timepicker: false,
        format: 'Y-m-d',
        closeOnDateSelect: true,
        scrollInput: false,
        dayOfWeekStart: 1,
        inline: true,
        scrollMonth: false,
    });
    initNumberFormatter('#amount', {
        thousandSeparator: '{{ config('money.thousands_separator') }}',
        decimalSeparator: '{{ config('money.decimal_separator') }}'
    });
})();
</script>
@endpush
