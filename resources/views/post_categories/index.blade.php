@extends('layouts.settings')

@section('title', __('post_category.list'))

@section('content_settings')
<div class="page-header">
    <h1 class="page-title">{{ __('post_category.list') }}</h1>
    <div class="page-options d-flex">
        @can('create', new App\Models\PostCategory)
            {{ link_to_route('post_categories.index', __('post_category.create'), ['action' => 'create'], ['class' => 'btn btn-success']) }}
        @endcan
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card table-responsive">
            <table class="table table-sm table-responsive-sm table-hover mb-0">
                <thead>
                    <tr>
                        <th class="text-center">{{ __('app.table_no') }}</th>
                        <th>{{ __('post_category.name') }}</th>
                        <th class="text-center">{{ __('post_category.posts_count') }}</th>
                        <th class="text-center">{{ __('app.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $key => $category)
                    <tr>
                        <td class="text-center">{{ 1 + $key }}</td>
                        <td>{{ $category->name }}</td>
                        <td class="text-center">{{ $category->posts_count }}</td>
                        <td class="text-center text-nowrap">
                            @can('update', $category)
                                {{ link_to_route(
                                    'post_categories.index',
                                    __('app.edit'),
                                    ['action' => 'edit', 'id' => $category->id],
                                    ['id' => 'edit-post_category-'.$category->id, 'class' => 'btn btn-sm btn-warning']
                                ) }}
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4">{{ __('post_category.not_found') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('post_categories.forms')
@endsection

@push('scripts')
<script>
(function () {
    $('#postCategoryModal').modal({
        show: true,
        backdrop: 'static',
    });
})();
</script>
@endpush
