@extends('layouts.settings')

@section('title', __('post.create'))

@section('content_settings')
<div class="row justify-content-center mt-4">
    <div class="col-md-9">
        <div class="card">
            <div class="card-header">{{ __('post.create') }}</div>
            {!! Form::open(['route' => 'posts.store', 'autocomplete' => 'off', 'files' => true]) !!}
            <div class="card-body">
                {!! FormField::radios('type_code', [
                    App\Models\Post::TYPE_PAGE => __('post.page'),
                    App\Models\Post::TYPE_NEWS => __('post.news'),
                ], [
                    'required' => true,
                    'label' => __('post.type_code'),
                    'value' => old('type_code', $typeCode),
                    'id' => 'type_code_field',
                ]) !!}
                <div id="category_field" style="display:none">
                    {!! FormField::select('category_id', $categories, [
                        'label' => __('post.category_id'),
                        'value' => old('category_id'),
                    ]) !!}
                </div>
                {!! FormField::text('title', [
                    'required' => true,
                    'label' => __('post.title'),
                    'value' => old('title'),
                ]) !!}
                {!! FormField::text('slug', [
                    'label' => __('post.slug'),
                    'value' => old('slug'),
                    'placeholder' => 'kosongkan-untuk-otomatis-dari-judul',
                ]) !!}
                {!! FormField::textarea('excerpt', [
                    'label' => __('post.excerpt'),
                    'value' => old('excerpt'),
                ]) !!}
                {!! FormField::textarea('content', [
                    'required' => true,
                    'label' => __('post.content'),
                    'value' => old('content'),
                    'id' => 'content',
                ]) !!}
                <div class="form-group {{ $errors->has('files.*') ? 'has-error' : '' }}">
                    <label for="files" class="form-label fw-bold">{{ __('post.upload_featured_image') }}</label>
                    {{ Form::file('files[]', ['multiple' => true, 'class' => 'form-control-file border p-2 rounded '.($errors->has('files.*') ? 'is-invalid' : '')]) }}
                    @if ($errors->has('files.*'))
                        @foreach ($errors->get('files.*') as $key => $errorMessages)
                            {!! $errors->first($key, '<span class="invalid-feedback" role="alert">:message</span>') !!}
                        @endforeach
                    @endif
                </div>
                <hr>
                <h5 class="text-muted">SEO</h5>
                {!! FormField::text('meta_title', ['label' => __('post.meta_title'), 'value' => old('meta_title')]) !!}
                {!! FormField::textarea('meta_description', ['label' => __('post.meta_description'), 'value' => old('meta_description')]) !!}
                {!! FormField::radios('status_id', [
                    App\Models\Post::STATUS_DRAFT => __('post.status_draft'),
                    App\Models\Post::STATUS_PUBLISHED => __('post.status_published'),
                ], [
                    'label' => __('app.status'),
                    'value' => old('status_id', App\Models\Post::STATUS_DRAFT),
                ]) !!}
            </div>
            <div class="card-footer">
                {!! Form::submit(__('post.create'), ['class' => 'btn btn-success']) !!}
                {{ link_to_route('posts.index', __('app.cancel'), [], ['class' => 'btn btn-link']) }}
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection

@section('styles')
    {{ Html::style(url('https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs4.min.css')) }}
@endsection

@push('scripts')
    {{ Html::script(url('https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs4.min.js')) }}
<script>
(function () {
    $('#content').summernote({ tabsize: 2, height: 300 });

    function toggleCategoryField() {
        var isNews = $('input[name="type_code"]:checked').val() === '{{ App\Models\Post::TYPE_NEWS }}';
        $('#category_field').toggle(isNews);
    }
    $('input[name="type_code"]').on('change', toggleCategoryField);
    toggleCategoryField();
})();
</script>
@endpush
