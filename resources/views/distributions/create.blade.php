@extends('layouts.ziswaf')

@section('title', __('distribution.create'))

@section('content_ziswaf')
<div class="row justify-content-center mt-4">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">{{ __('distribution.create') }}</div>
            {!! Form::open(['route' => 'distributions.store', 'autocomplete' => 'off', 'files' => true]) !!}
            <div class="card-body">
                {!! FormField::select('book_id', $fundBooks, [
                    'required' => true,
                    'label' => __('distribution.fund_book'),
                    'value' => old('book_id'),
                    'id' => 'book_id',
                ]) !!}
                <p class="small text-muted" id="balance-info"></p>

                <div class="form-group {{ $errors->has('category_id') ? 'has-error' : '' }}">
                    {{ Form::label('category_id', __('distribution.asnaf'), ['class' => 'form-label']) }}
                    @foreach ($fundBooks as $bookId => $bookName)
                        {{ Form::select('category_id', ['' => '-- '.__('distribution.asnaf').' --'] + ($asnafCategoriesByBook->get($bookId) ?? collect())->pluck('name', 'id')->toArray(), old('category_id'), [
                            'id' => 'category_id_'.$bookId,
                            'class' => 'form-control category-select',
                            'data-book-id' => $bookId,
                            'style' => 'display:none',
                            'disabled' => true,
                        ]) }}
                    @endforeach
                    {!! $errors->first('category_id', '<span class="invalid-feedback d-block" role="alert">:message</span>') !!}
                </div>

                {!! FormField::text('title', [
                    'required' => true,
                    'label' => __('distribution.title'),
                    'value' => old('title'),
                ]) !!}
                <div class="row">
                    <div class="col-md-6">
                        {!! FormField::price('amount', [
                            'required' => true,
                            'label' => __('distribution.amount'),
                            'addon' => ['before' => config('money.currency_code')],
                            'step' => number_step(),
                            'value' => old('amount'),
                        ]) !!}
                    </div>
                    <div class="col-md-6">
                        {!! FormField::text('distribution_date', [
                            'required' => true,
                            'label' => __('distribution.distribution_date'),
                            'value' => old('distribution_date', now()->format('Y-m-d')),
                            'class' => 'date-select',
                        ]) !!}
                    </div>
                </div>
                {!! FormField::textarea('description', [
                    'label' => __('app.description'),
                    'value' => old('description'),
                ]) !!}
                <div class="form-group {{ $errors->has('files.*') ? 'has-error' : '' }}">
                    <label for="files" class="form-label fw-bold">{{ __('distribution.upload_documentation') }}</label>
                    @if($isDiskFull)
                        <div class="alert alert-warning my-2 p-2" role="alert">{{ __('transaction.disk_is_full') }}</div>
                    @else
                        {{ Form::file('files[]', ['multiple' => true, 'class' => 'form-control-file border p-2 rounded '.($errors->has('files.*') ? 'is-invalid' : '')]) }}
                        @if ($errors->has('files.*'))
                            @foreach ($errors->get('files.*') as $key => $errorMessages)
                                {!! $errors->first($key, '<span class="invalid-feedback" role="alert">:message</span>') !!}
                            @endforeach
                        @endif
                    @endif
                </div>
            </div>
            <div class="card-footer">
                {!! Form::submit(__('distribution.create'), ['class' => 'btn btn-success']) !!}
                {{ link_to_route('distributions.index', __('app.cancel'), [], ['class' => 'btn btn-link']) }}
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
    var balances = @json($bookBalances);
    var currency = @json(config('money.currency_code'));

    function showCategoryForBook(bookId) {
        $('.category-select').hide().prop('disabled', true).prop('name', '');
        var $selected = $('.category-select[data-book-id="'+bookId+'"]');
        $selected.show().prop('disabled', false).prop('name', 'category_id');
    }

    function showBalanceForBook(bookId) {
        if (bookId && balances[bookId] !== undefined) {
            $('#balance-info').text('{{ __('distribution.available_balance') }}: ' + currency + ' ' + Number(balances[bookId]).toLocaleString('id-ID'));
        } else {
            $('#balance-info').text('');
        }
    }

    $('#book_id').on('change', function () {
        showCategoryForBook($(this).val());
        showBalanceForBook($(this).val());
    });

    // Restore selection on validation-error redisplay.
    var initialBookId = $('#book_id').val();
    if (initialBookId) {
        showCategoryForBook(initialBookId);
        showBalanceForBook(initialBookId);
    }

    $('.date-select').datetimepicker({
        timepicker: false,
        format: 'Y-m-d',
        closeOnDateSelect: true,
        scrollInput: false,
        dayOfWeekStart: 1,
    });
    initNumberFormatter('#amount', {
        thousandSeparator: '{{ config('money.thousands_separator') }}',
        decimalSeparator: '{{ config('money.decimal_separator') }}'
    });
})();
</script>
@endpush
