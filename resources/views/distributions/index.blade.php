@extends('layouts.ziswaf')

@section('title', __('distribution.list'))

@section('content_ziswaf')
<div class="page-header mt-4">
    <h1 class="page-title">{{ __('distribution.list') }}</h1>
    <div class="page-subtitle">{{ __('app.total') }} : {{ $distributions->total() }} {{ __('distribution.distribution') }}</div>
    <div class="page-options">
        @can('create', new App\Models\Distribution)
            {{ link_to_route('distributions.create', __('distribution.create'), [], ['class' => 'btn btn-success']) }}
        @endcan
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        {{ Form::open(['method' => 'get', 'class' => 'form-inline']) }}
            {{ Form::select('book_id', $fundBooks, request('book_id'), ['placeholder' => __('distribution.filter_book'), 'class' => 'form-control mr-0 mr-sm-2 mb-2 mb-sm-0']) }}
            {{ Form::select('status_id', [
                App\Models\Distribution::STATUS_PENDING => __('distribution.status_pending'),
                App\Models\Distribution::STATUS_APPROVED => __('distribution.status_approved'),
                App\Models\Distribution::STATUS_REJECTED => __('distribution.status_rejected'),
            ], request('status_id'), ['placeholder' => __('distribution.filter_status'), 'class' => 'form-control mr-0 mr-sm-2 mb-2 mb-sm-0']) }}
            {{ Form::submit(__('app.filter'), ['class' => 'btn btn-primary mr-0 mr-sm-2']) }}
            {{ link_to_route('distributions.index', __('app.reset'), [], ['class' => 'btn btn-secondary']) }}
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
                        <th class="text-nowrap">{{ __('distribution.distribution_date') }}</th>
                        <th class="text-nowrap">{{ __('distribution.fund_book') }}</th>
                        <th class="text-nowrap">{{ __('distribution.asnaf') }}</th>
                        <th class="text-nowrap">{{ __('distribution.title') }}</th>
                        <th class="text-nowrap text-right">{{ __('distribution.amount') }}</th>
                        <th class="text-center">{{ __('app.status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($distributions as $key => $distribution)
                    <tr>
                        <td class="text-center">{{ $distributions->firstItem() + $key }}</td>
                        <td class="text-nowrap">
                            {{ link_to_route('distributions.show', $distribution->distribution_date, [$distribution], ['id' => 'show-distribution-'.$distribution->id]) }}
                        </td>
                        <td class="text-nowrap">{{ $distribution->book->name }}</td>
                        <td class="text-nowrap">{{ optional($distribution->category)->name }}</td>
                        <td class="text-nowrap">{{ $distribution->title }}</td>
                        <td class="text-nowrap text-right">{{ config('money.currency_code') }} {{ $distribution->amount_string }}</td>
                        <td class="text-nowrap text-center">
                            <span class="badge {{ $distribution->status_id == App\Models\Distribution::STATUS_APPROVED ? 'badge-success' : ($distribution->status_id == App\Models\Distribution::STATUS_REJECTED ? 'badge-danger' : 'badge-warning') }}">
                                {{ $distribution->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7">{{ __('distribution.not_found') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="card-body">{{ $distributions->links() }}</div>
        </div>
    </div>
</div>
@endsection
