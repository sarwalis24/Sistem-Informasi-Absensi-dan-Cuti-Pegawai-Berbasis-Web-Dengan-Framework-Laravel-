@props([
    'icon' => '',
    'name' => '',
    'label' => '',
    'value' => '',
    'readonly' => false,
    'type' => 'text',
    'align' => '',
    'disabled' => false,
    'money' => false,
    'datepicker' => '',
])
<div class="form-group mb-3 row">
    <label style="font-weight: 600"class="col-md-4 col-form-label">{{ $label }}</label>
    <div class="col-md-8">
        <div class="input-group input-group-merge">
            <span class="input-group-text" id="basic-addon-search31"><i class="{{ $icon }}"></i></span>
            <input type="{{ $type }}" class="form-control {{ $money ? 'money' : '' }} {{ $datepicker }}"
                id="{{ $name }}" name="{{ $name }}" placeholder="{{ $label }}"
                {{ $readonly ? 'readonly' : '' }} {{ $disabled ? 'disabled' : '' }} autocomplete="off"
                aria-autocomplete="none" value="{{ $value }}" style="text-align: {{ $align }}">
        </div>
    </div>
</div>
