@extends('layouts.settings')

@section('title', __('menu.list'))

@section('content_settings')
<div class="page-header">
    <h1 class="page-title">{{ __('menu.list') }}</h1>
    <div class="page-options d-flex">
        @can('create', new App\Models\Menu)
            {{ link_to_route('menus.index', __('menu.create'), ['action' => 'create'], ['class' => 'btn btn-success']) }}
        @endcan
    </div>
</div>
<p class="text-muted">{{ __('menu.drag_to_reorder') }}</p>

<div class="row">
    <div class="col-md-12">
        <div class="card table-responsive">
            <table class="table table-sm table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width:90px" class="text-center">{{ __('menu.order') }}</th>
                        <th>{{ __('menu.label') }}</th>
                        <th>{{ __('menu.target_type') }}</th>
                        <th>{{ __('menu.target_value') }}</th>
                        <th class="text-center">{{ __('menu.is_active') }}</th>
                        <th class="text-center">{{ __('app.action') }}</th>
                    </tr>
                </thead>
                <tbody id="menu-rows">
                    @forelse ($menus as $menu)
                    <tr data-id="{{ $menu->id }}">
                        <td class="text-center text-nowrap">
                            <button type="button" class="btn btn-sm btn-light move-up">&#9650;</button>
                            <button type="button" class="btn btn-sm btn-light move-down">&#9660;</button>
                        </td>
                        <td>{{ $menu->label }}</td>
                        <td>{{ __('menu.target_type_'.$menu->target_type) }}</td>
                        <td class="text-nowrap">
                            <code>{{ $menu->target_value }}</code>
                            @if (!$menu->url)
                                <span class="badge badge-danger" title="{{ __('menu.broken_link_notice') }}">!</span>
                            @endif
                        </td>
                        <td class="text-center">{{ $menu->is_active ? __('app.yes') : __('app.no') }}</td>
                        <td class="text-center text-nowrap">
                            @can('update', $menu)
                                {{ link_to_route('menus.index', __('app.edit'), ['action' => 'edit', 'id' => $menu->id], ['id' => 'edit-menu-'.$menu->id, 'class' => 'btn btn-sm btn-warning']) }}
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6">{{ __('menu.not_found') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('menus.forms')
@endsection

@push('scripts')
<script>
(function () {
    $('#menuModal').modal({
        show: true,
        backdrop: 'static',
    });

    function saveOrder() {
        var ids = $('#menu-rows tr[data-id]').map(function () { return $(this).data('id'); }).get();
        $.ajax({
            url: '{{ route('menus.reorder') }}',
            method: 'PATCH',
            data: { ordered_ids: ids, _token: '{{ csrf_token() }}' },
            success: function () {
                if (typeof noty === 'function') {
                    noty({ type: 'success', layout: 'bottomRight', text: '{{ __('menu.reordered') }}', timeout: 2000 });
                }
            }
        });
    }

    $('#menu-rows').on('click', '.move-up', function () {
        var $row = $(this).closest('tr');
        var $prev = $row.prev('tr[data-id]');
        if ($prev.length) {
            $row.insertBefore($prev);
            saveOrder();
        }
    });
    $('#menu-rows').on('click', '.move-down', function () {
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
