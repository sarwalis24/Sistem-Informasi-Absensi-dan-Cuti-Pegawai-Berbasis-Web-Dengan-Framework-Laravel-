@props([
    'name' => '',
    'label' => '',
    'value' => '',
])
<div class="form-group mb-3">
    <label for="exampleFormControlInput1" style="font-weight: 600" class="form-label">{{ $label }}</label>
    <textarea class="form-control" name="{{ $name }}" id="{{ $name }}" placeholder="{{ $label }}"
        rows="2">{{ $value }}</textarea>
</div>
