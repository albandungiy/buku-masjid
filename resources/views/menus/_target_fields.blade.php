{{-- Shared by menus/forms.blade.php for both create & edit. $idSuffix keeps element ids
     unique when both modals could theoretically exist in the DOM at once.
     target_value is submitted by exactly one of 3 inputs sharing that name — the other
     two are disabled (so they don't submit), mirroring the pattern used in
     distributions/create.blade.php for its per-book Asnaf category select. --}}
<div class="modal-body">
    {!! FormField::text('label', ['required' => true, 'label' => __('menu.label'), 'value' => old('label', $editableMenu->label ?? null)]) !!}
    {!! FormField::select('target_type', [
        App\Models\Menu::TARGET_ROUTE => __('menu.target_type_route'),
        App\Models\Menu::TARGET_POST => __('menu.target_type_post'),
        App\Models\Menu::TARGET_URL => __('menu.target_type_url'),
    ], [
        'required' => true,
        'label' => __('menu.target_type'),
        'placeholder' => false,
        'id' => 'target_type_'.$idSuffix,
        'value' => $currentTargetType,
    ]) !!}
    <div class="form-group">
        {{ Form::label('target_value_'.$idSuffix.'_route', __('menu.target_value'), ['class' => 'form-label']) }}
        {{ Form::text('target_value', $currentTargetType == App\Models\Menu::TARGET_ROUTE ? $currentTargetValue : null, [
            'id' => 'target_value_'.$idSuffix.'_route',
            'class' => 'form-control target-value-input',
            'placeholder' => __('menu.target_value_route_placeholder'),
            'style' => $currentTargetType == App\Models\Menu::TARGET_ROUTE ? '' : 'display:none',
            'disabled' => $currentTargetType != App\Models\Menu::TARGET_ROUTE,
        ]) }}
        {{ Form::select('target_value', $posts, $currentTargetType == App\Models\Menu::TARGET_POST ? $currentTargetValue : null, [
            'id' => 'target_value_'.$idSuffix.'_post',
            'class' => 'form-control target-value-input',
            'style' => $currentTargetType == App\Models\Menu::TARGET_POST ? '' : 'display:none',
            'disabled' => $currentTargetType != App\Models\Menu::TARGET_POST,
        ]) }}
        {{ Form::text('target_value', $currentTargetType == App\Models\Menu::TARGET_URL ? $currentTargetValue : null, [
            'id' => 'target_value_'.$idSuffix.'_url',
            'class' => 'form-control target-value-input',
            'placeholder' => __('menu.target_value_url_placeholder'),
            'style' => $currentTargetType == App\Models\Menu::TARGET_URL ? '' : 'display:none',
            'disabled' => $currentTargetType != App\Models\Menu::TARGET_URL,
        ]) }}
        {!! $errors->first('target_value', '<span class="invalid-feedback d-block" role="alert">:message</span>') !!}
    </div>
</div>

<script>
(function () {
    function toggleTargetFields() {
        var selected = $('#target_type_{{ $idSuffix }}').val();
        $('#target_value_{{ $idSuffix }}_route').toggle(selected === '{{ App\Models\Menu::TARGET_ROUTE }}').prop('disabled', selected !== '{{ App\Models\Menu::TARGET_ROUTE }}');
        $('#target_value_{{ $idSuffix }}_post').toggle(selected === '{{ App\Models\Menu::TARGET_POST }}').prop('disabled', selected !== '{{ App\Models\Menu::TARGET_POST }}');
        $('#target_value_{{ $idSuffix }}_url').toggle(selected === '{{ App\Models\Menu::TARGET_URL }}').prop('disabled', selected !== '{{ App\Models\Menu::TARGET_URL }}');
    }
    $('#target_type_{{ $idSuffix }}').on('change', toggleTargetFields);
    toggleTargetFields();
})();
</script>
