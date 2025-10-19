@props([
    'name' => '',
    'label' => '',
    'value' => '',
])
<div class="form-group mb-3">
    <textarea class="form-control" name="{{ $name }}" id="{{ $name }}" placeholder="{{ $label }}"
        rows="2">{{ $value }}</textarea>
</div>
