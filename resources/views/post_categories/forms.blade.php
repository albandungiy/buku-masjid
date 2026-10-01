@if (request('action') == 'create')
@can('create', new App\Models\PostCategory)
    <div id="postCategoryModal" class="modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('post_category.create') }}</h5>
                    {{ link_to_route('post_categories.index', '', [], ['class' => 'close']) }}
                </div>
                {!! Form::open(['route' => 'post_categories.store']) !!}
                <div class="modal-body">
                    {!! FormField::text('name', ['required' => true, 'label' => __('post_category.name')]) !!}
                </div>
                <div class="modal-footer">
                    {!! Form::submit(__('post_category.create'), ['class' => 'btn btn-success']) !!}
                    {{ link_to_route('post_categories.index', __('app.cancel'), [], ['class' => 'btn btn-secondary']) }}
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endcan
@endif

@if (request('action') == 'edit' && $editableCategory)
@can('update', $editableCategory)
    <div id="postCategoryModal" class="modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('post_category.edit') }}</h5>
                    {{ link_to_route('post_categories.index', '', [], ['class' => 'close']) }}
                </div>
                {!! Form::model($editableCategory, ['route' => ['post_categories.update', $editableCategory], 'method' => 'patch']) !!}
                <div class="modal-body">
                    {!! FormField::text('name', ['required' => true, 'label' => __('post_category.name')]) !!}
                </div>
                <div class="modal-footer">
                    {!! Form::submit(__('post_category.updated'), ['class' => 'btn btn-success']) !!}
                    {{ link_to_route('post_categories.index', __('app.cancel'), [], ['class' => 'btn btn-secondary']) }}
                    @can('delete', $editableCategory)
                        {!! FormField::delete(
                            ['route' => ['post_categories.destroy', $editableCategory], 'onsubmit' => __('post_category.delete_confirm')],
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
