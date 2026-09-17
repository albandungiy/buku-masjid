@extends('layouts.ziswaf')

@section('title', __('donation.list'))

@section('content_ziswaf')
<div class="page-header mt-4">
    <h1 class="page-title">{{ __('donation.list') }}</h1>
    <div class="page-subtitle">{{ __('app.total') }} : {{ $donations->total() }} {{ __('donation.donation') }}</div>
    <div class="page-options">
        @can('create', new App\Models\Donation)
            {{ link_to_route('donations.create', __('donation.create'), [], ['class' => 'btn btn-success']) }}
        @endcan
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        {{ Form::open(['method' => 'get', 'class' => 'form-inline']) }}
            {{ Form::select('book_id', $fundBooks, request('book_id'), ['placeholder' => __('donation.filter_book'), 'class' => 'form-control mr-0 mr-sm-2 mb-2 mb-sm-0']) }}
            {{ Form::select('status_id', [
                App\Models\Donation::STATUS_PENDING => __('donation.status_pending'),
                App\Models\Donation::STATUS_CONFIRMED => __('donation.status_confirmed'),
                App\Models\Donation::STATUS_FAILED => __('donation.status_failed'),
            ], request('status_id'), ['placeholder' => __('donation.filter_status'), 'class' => 'form-control mr-0 mr-sm-2 mb-2 mb-sm-0']) }}
            {{ Form::submit(__('app.filter'), ['class' => 'btn btn-primary mr-0 mr-sm-2']) }}
            {{ link_to_route('donations.index', __('app.reset'), [], ['class' => 'btn btn-secondary']) }}
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
                        <th class="text-nowrap">{{ __('app.date') }}</th>
                        <th class="text-nowrap">{{ __('donation.fund_book') }}</th>
                        <th class="text-nowrap">{{ __('donation.muzakki') }}</th>
                        <th class="text-nowrap text-right">{{ __('donation.amount') }}</th>
                        <th class="text-center">{{ __('app.status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($donations as $key => $donation)
                    <tr>
                        <td class="text-center">{{ $donations->firstItem() + $key }}</td>
                        <td class="text-nowrap">
                            {{ link_to_route('donations.show', $donation->date, [$donation], ['id' => 'show-donation-'.$donation->id]) }}
                        </td>
                        <td class="text-nowrap">{{ $donation->book->name }}</td>
                        <td class="text-nowrap">{{ optional($donation->partner)->name ?? __('donation.anonymous') }}</td>
                        <td class="text-nowrap text-right">{{ config('money.currency_code') }} {{ $donation->amount_string }}</td>
                        <td class="text-nowrap text-center">
                            <span class="badge {{ $donation->status_id == App\Models\Donation::STATUS_CONFIRMED ? 'badge-success' : ($donation->status_id == App\Models\Donation::STATUS_FAILED ? 'badge-danger' : 'badge-warning') }}">
                                {{ $donation->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6">{{ __('donation.not_found') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="card-body">{{ $donations->links() }}</div>
        </div>
    </div>
</div>
@endsection
