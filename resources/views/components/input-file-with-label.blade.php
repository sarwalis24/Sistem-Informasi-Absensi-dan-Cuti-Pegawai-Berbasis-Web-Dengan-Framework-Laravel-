@props(['name' => '', 'label' => '', 'value' => ''])
<div class="form-group mb-3">
    <label for="exampleFormControlInput1" style="font-weight: 600" class="form-label">{{ $label }}</label>
    <input class="form-control" type="file" id="{{ $name }}" name="{{ $name }}">
</div>
