@if (request('action') == 'create')
@can('create', new App\Models\RunningText)
    <div id="runningTextModal" class="modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('running_text.create') }}</h5>
                    {{ link_to_route('running_texts.index', '', [], ['class' => 'close']) }}
                </div>
                {!! Form::open(['route' => 'running_texts.store']) !!}
                <div class="modal-body">
                    {!! FormField::textarea('content', ['required' => true, 'label' => __('running_text.content'), 'placeholder' => __('running_text.content_placeholder'), 'maxlength' => 255]) !!}
                </div>
                <div class="modal-footer">
                    {!! Form::submit(__('running_text.create'), ['class' => 'btn btn-success']) !!}
                    {{ link_to_route('running_texts.index', __('app.cancel'), [], ['class' => 'btn btn-secondary']) }}
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endcan
@endif

@if (request('action') == 'edit' && $editableRunningText)
@can('update', $editableRunningText)
    <div id="runningTextModal" class="modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('running_text.edit') }}</h5>
                    {{ link_to_route('running_texts.index', '', [], ['class' => 'close']) }}
                </div>
                {!! Form::model($editableRunningText, ['route' => ['running_texts.update', $editableRunningText], 'method' => 'patch']) !!}
                <div class="modal-body">
                    {!! FormField::textarea('content', ['required' => true, 'label' => __('running_text.content'), 'maxlength' => 255]) !!}
                    {!! FormField::radios('is_active', [1 => __('app.active'), 0 => __('app.inactive')], [
                        'label' => __('running_text.is_active'),
                        'value' => old('is_active', $editableRunningText->is_active ? 1 : 0),
                    ]) !!}
                </div>
                <div class="modal-footer">
                    {!! Form::submit(__('app.update'), ['class' => 'btn btn-success']) !!}
                    {{ link_to_route('running_texts.index', __('app.cancel'), [], ['class' => 'btn btn-secondary']) }}
                    @can('delete', $editableRunningText)
                        {!! FormField::delete(
                            ['route' => ['running_texts.destroy', $editableRunningText], 'onsubmit' => __('running_text.delete_confirm')],
                            __('app.delete'),
                            ['class' => 'btn btn-danger float-left']
                        ) !!}
                    @endcan
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endcan
@endif
