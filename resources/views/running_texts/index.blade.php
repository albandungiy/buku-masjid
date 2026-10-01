@extends('layouts.settings')

@section('title', __('running_text.list'))

@section('content_settings')
<div class="page-header">
    <h1 class="page-title">{{ __('running_text.list') }}</h1>
    <div class="page-options d-flex">
        @can('create', new App\Models\RunningText)
            {{ link_to_route('running_texts.index', __('running_text.create'), ['action' => 'create'], ['class' => 'btn btn-success']) }}
        @endcan
    </div>
</div>
<p class="text-muted">{{ __('running_text.info_text') }}</p>

<div class="row">
    <div class="col-md-12">
        <div class="card table-responsive">
            <table class="table table-sm table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width:90px" class="text-center">{{ __('menu.order') }}</th>
                        <th>{{ __('running_text.content') }}</th>
                        <th class="text-center">{{ __('running_text.is_active') }}</th>
                        <th class="text-center">{{ __('app.action') }}</th>
                    </tr>
                </thead>
                <tbody id="running-text-rows">
                    @forelse ($runningTexts as $runningText)
                    <tr data-id="{{ $runningText->id }}">
                        <td class="text-center text-nowrap">
                            <button type="button" class="btn btn-sm btn-light move-up">&#9650;</button>
                            <button type="button" class="btn btn-sm btn-light move-down">&#9660;</button>
                        </td>
                        <td>{{ $runningText->content }}</td>
                        <td class="text-center">{{ $runningText->is_active ? __('app.yes') : __('app.no') }}</td>
                        <td class="text-center text-nowrap">
                            @can('update', $runningText)
                                {{ link_to_route('running_texts.index', __('app.edit'), ['action' => 'edit', 'id' => $runningText->id], ['id' => 'edit-running_text-'.$runningText->id, 'class' => 'btn btn-sm btn-warning']) }}
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4">{{ __('running_text.not_found') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('running_texts.forms')
@endsection

@push('scripts')
<script>
(function () {
    $('#runningTextModal').modal({
        show: true,
        backdrop: 'static',
    });

    function saveOrder() {
        var ids = $('#running-text-rows tr[data-id]').map(function () { return $(this).data('id'); }).get();
        $.ajax({
            url: '{{ route('running_texts.reorder') }}',
            method: 'PATCH',
            data: { ordered_ids: ids, _token: '{{ csrf_token() }}' },
            success: function () {
                if (typeof noty === 'function') {
                    noty({ type: 'success', layout: 'bottomRight', text: '{{ __('running_text.reordered') }}', timeout: 2000 });
                }
            }
        });
    }

    $('#running-text-rows').on('click', '.move-up', function () {
        var $row = $(this).closest('tr');
        var $prev = $row.prev('tr[data-id]');
        if ($prev.length) {
            $row.insertBefore($prev);
            saveOrder();
        }
    });
    $('#running-text-rows').on('click', '.move-down', function () {
        var $row = $(this).closest('tr');
        var $next = $row.next('tr[data-id]');
        if ($next.length) {
            $row.insertAfter($next);
            saveOrder();
        }
    });
})();
</script>
@endpush
