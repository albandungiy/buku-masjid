@if (request('action') == 'create')
@can('create', new App\Models\Menu)
    <div id="menuModal" class="modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('menu.create') }}</h5>
                    {{ link_to_route('menus.index', '', [], ['class' => 'close']) }}
                </div>
                {!! Form::open(['route' => 'menus.store', 'id' => 'menu-create-form']) !!}
                @include('menus._target_fields', ['idSuffix' => 'create', 'currentTargetType' => old('target_type'), 'currentTargetValue' => old('target_value')])
                <div class="modal-footer">
                    {!! Form::submit(__('menu.create'), ['class' => 'btn btn-success']) !!}
                    {{ link_to_route('menus.index', __('app.cancel'), [], ['class' => 'btn btn-secondary']) }}
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endcan
@endif

@if (request('action') == 'edit' && $editableMenu)
@can('update', $editableMenu)
    <div id="menuModal" class="modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('menu.edit') }}</h5>
                    {{ link_to_route('menus.index', '', [], ['class' => 'close']) }}
                </div>
                {!! Form::model($editableMenu, ['route' => ['menus.update', $editableMenu], 'method' => 'patch', 'id' => 'menu-edit-form']) !!}
                @include('menus._target_fields', [
                    'idSuffix' => 'edit',
                    'currentTargetType' => old('target_type', $editableMenu->target_type),
                    'currentTargetValue' => old('target_value', $editableMenu->target_value),
                ])
                {!! FormField::radios('is_active', [1 => __('app.active'), 0 => __('app.inactive')], [
                    'label' => __('menu.is_active'),
                    'value' => old('is_active', $editableMenu->is_active ? 1 : 0),
                ]) !!}
                <div class="modal-footer">
                    {!! Form::submit(__('app.update'), ['class' => 'btn btn-success']) !!}
                    {{ link_to_route('menus.index', __('app.cancel'), [], ['class' => 'btn btn-secondary']) }}
                    @can('delete', $editableMenu)
                        {!! FormField::delete(
                            ['route' => ['menus.destroy', $editableMenu], 'onsubmit' => __('menu.delete_confirm')],
                            __('app.delete'),
                            ['class' => 'btn btn-danger float-left'],
                            ['menu_id' => $editableMenu->id]
                        ) !!}
                    @endcan
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endcan
@endif
