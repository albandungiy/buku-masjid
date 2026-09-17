@extends('layouts.ziswaf')

@section('title', __('distribution.detail').' #'.$distribution->id)

@section('content_ziswaf')
<div class="page-header mt-4">
    <h1 class="page-title">{{ __('distribution.distribution') }} #{{ $distribution->id }}</h1>
    <div class="page-subtitle">{{ __('distribution.detail') }}</div>
    <div class="page-options">
        @can('approve', $distribution)
            @can('manage-distributions', $distribution->book)
                {!! FormField::formButton(
                    ['route' => ['distributions.approve', $distribution], 'method' => 'patch', 'onsubmit' => __('distribution.approve_confirm')],
                    __('distribution.approve'),
                    ['class' => 'btn btn-success mr-2 mt-2 mt-lg-0', 'id' => 'approve-distribution-'.$distribution->id]
                ) !!}
            @endcan
        @endcan
        @can('reject', $distribution)
            @can('manage-distributions', $distribution->book)
                {!! FormField::formButton(
                    ['route' => ['distributions.reject', $distribution], 'method' => 'patch', 'onsubmit' => __('distribution.reject_confirm')],
                    __('distribution.reject'),
                    ['class' => 'btn btn-warning text-dark mr-2 mt-2 mt-lg-0', 'id' => 'reject-distribution-'.$distribution->id]
                ) !!}
            @endcan
        @endcan
        @can('delete', $distribution)
            @can('manage-distributions', $distribution->book)
                {!! FormField::delete(
                    ['route' => ['distributions.destroy', $distribution], 'onsubmit' => __('distribution.delete_confirm')],
                    __('app.delete'),
                    ['class' => 'btn btn-danger mr-2 mt-2 mt-lg-0', 'id' => 'delete-distribution-'.$distribution->id],
                    ['distribution_id' => $distribution->id]
                ) !!}
            @endcan
        @endcan
        {{ link_to_route('distributions.index', __('distribution.back_to_index'), [], ['class' => 'btn btn-secondary mt-2 mt-lg-0']) }}
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card card-default">
            <table class="table card-table table-sm">
                <tbody>
                    <tr><td class="col-5">{{ __('distribution.fund_book') }}</td><td>{{ $distribution->book->name }}</td></tr>
                    <tr><td>{{ __('distribution.asnaf') }}</td><td>{{ optional($distribution->category)->name }}</td></tr>
                    <tr><td>{{ __('distribution.title') }}</td><td>{{ $distribution->title }}</td></tr>
                    <tr><td>{{ __('distribution.amount') }}</td><td class="lead">{{ config('money.currency_code') }} {{ $distribution->amount_string }}</td></tr>
                    <tr><td>{{ __('distribution.distribution_date') }}</td><td>{{ $distribution->distribution_date }}</td></tr>
                    <tr>
                        <td>{{ __('app.status') }}</td>
                        <td>
                            <span class="badge {{ $distribution->status_id == App\Models\Distribution::STATUS_APPROVED ? 'badge-success' : ($distribution->status_id == App\Models\Distribution::STATUS_REJECTED ? 'badge-danger' : 'badge-warning') }}">
                                {{ $distribution->status }}
                            </span>
                        </td>
                    </tr>
                    @if ($distribution->status_id == App\Models\Distribution::STATUS_APPROVED)
                        <tr><td>{{ __('distribution.approved_by') }}</td><td>{{ optional($distribution->approver)->name }}</td></tr>
                        <tr><td>{{ __('distribution.approved_at') }}</td><td>{{ $distribution->approved_at }}</td></tr>
                    @endif
                    <tr><td>{{ __('app.description') }}</td><td>{!! nl2br(htmlentities($distribution->description ?? '')) !!}</td></tr>
                    <tr><td>{{ __('app.created_by') }}</td><td>{{ $distribution->creator->name }}</td></tr>
                    <tr><td>{{ __('app.created_at') }}</td><td>{{ $distribution->created_at }}</td></tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    {{ __('distribution.documentation') }}
                    @if (!$distribution->files->isEmpty())
                        ({{ $distribution->files->count() }})
                    @endif
                </h3>
            </div>
        </div>
        <div class="row">
            @forelse ($distribution->files as $file)
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            @if (in_array($file->type_code, ['raw_image', 'image']))
                                <div class="mb-2 text-center">
                                    <a href="{{ asset('storage/'.$file->file_path) }}">
                                        <img src="{{ asset('storage/'.$file->file_path) }}" alt="{{ __('distribution.documentation') }}" class="img-fluid">
                                    </a>
                                </div>
                            @else
                                <a href="{{ asset('storage/'.$file->file_path) }}" target="_blank"><i class="fe fe-file"></i> {{ __('distribution.documentation') }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-muted p-3">{{ __('distribution.documentation') }}: -</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
