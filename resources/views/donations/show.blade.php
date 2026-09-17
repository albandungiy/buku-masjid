@extends('layouts.ziswaf')

@section('title', __('donation.detail').' #'.$donation->id)

@section('content_ziswaf')
<div class="page-header mt-4">
    <h1 class="page-title">{{ __('donation.donation') }} #{{ $donation->id }}</h1>
    <div class="page-subtitle">{{ __('donation.detail') }}</div>
    <div class="page-options">
        @can('confirm', $donation)
            @can('manage-donations', $donation->book)
                {!! FormField::formButton(
                    ['route' => ['donations.confirm', $donation], 'method' => 'patch', 'onsubmit' => __('donation.confirm_confirm')],
                    __('donation.confirm'),
                    ['class' => 'btn btn-success mr-2 mt-2 mt-lg-0', 'id' => 'confirm-donation-'.$donation->id]
                ) !!}
            @endcan
        @endcan
        @can('delete', $donation)
            @can('manage-donations', $donation->book)
                {!! FormField::delete(
                    ['route' => ['donations.destroy', $donation], 'onsubmit' => __('donation.delete_confirm')],
                    __('app.delete'),
                    ['class' => 'btn btn-danger mr-2 mt-2 mt-lg-0', 'id' => 'delete-donation-'.$donation->id],
                    ['donation_id' => $donation->id]
                ) !!}
            @endcan
        @endcan
        {{ link_to_route('donations.index', __('donation.back_to_index'), [], ['class' => 'btn btn-secondary mt-2 mt-lg-0']) }}
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card card-default">
            <table class="table card-table table-sm">
                <tbody>
                    <tr><td class="col-5">{{ __('donation.id') }}</td><td>#{{ $donation->id }}</td></tr>
                    <tr><td>{{ __('donation.fund_book') }}</td><td>{{ $donation->book->name }}</td></tr>
                    <tr><td>{{ __('donation.muzakki') }}</td><td>{{ optional($donation->partner)->name ?? __('donation.anonymous') }}</td></tr>
                    <tr><td>{{ __('donation.date') }}</td><td>{{ $donation->date }}</td></tr>
                    <tr><td>{{ __('donation.amount') }}</td><td class="lead">{{ config('money.currency_code') }} {{ $donation->amount_string }}</td></tr>
                    <tr><td>{{ __('donation.payment_method') }}</td><td>{{ config('ziswaf.payment_methods')[$donation->payment_method_code] ?? $donation->payment_method_code }}</td></tr>
                    <tr>
                        <td>{{ __('app.status') }}</td>
                        <td>
                            <span class="badge {{ $donation->status_id == App\Models\Donation::STATUS_CONFIRMED ? 'badge-success' : ($donation->status_id == App\Models\Donation::STATUS_FAILED ? 'badge-danger' : 'badge-warning') }}">
                                {{ $donation->status }}
                            </span>
                        </td>
                    </tr>
                    @if ($donation->status_id == App\Models\Donation::STATUS_CONFIRMED)
                        <tr><td>{{ __('donation.net_amount') }}</td><td>{{ config('money.currency_code') }} {{ optional($donation->netTransaction)->amount_string }}</td></tr>
                        <tr><td>{{ __('donation.hak_amil_amount') }}</td><td>{{ config('money.currency_code') }} {{ optional($donation->hakAmilTransaction)->amount_string ?? format_number(0) }}</td></tr>
                        <tr><td>{{ __('donation.confirmed_at') }}</td><td>{{ $donation->confirmed_at }}</td></tr>
                    @endif
                    <tr><td>{{ __('app.created_by') }}</td><td>{{ $donation->creator->name }}</td></tr>
                    <tr><td>{{ __('app.created_at') }}</td><td>{{ $donation->created_at }}</td></tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    {{ __('donation.proof') }}
                    @if (!$donation->files->isEmpty())
                        ({{ $donation->files->count() }})
                    @endif
                </h3>
            </div>
        </div>
        <div class="row">
            @forelse ($donation->files as $file)
                @if (in_array($file->type_code, ['raw_image', 'image']))
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-2 text-center">
                                    <a href="{{ asset('storage/'.$file->file_path) }}">
                                        <img src="{{ asset('storage/'.$file->file_path) }}" alt="{{ __('donation.proof') }}" class="img-fluid">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @empty
                <div class="col-12 text-muted p-3">{{ __('donation.proof') }}: -</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
