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
    'numberFormat' => false,
    'datepicker' => '',
])
<div class="form-group mb-3">
    <div class="input-group input-group-merge">
        <span class="input-group-text" id="basic-addon-search31"><i class="{{ $icon }}"></i></span>
        <input type="{{ $type }}"
            class="form-control {{ $money ? 'money' : '' }} {{ $numberFormat ? 'number-separator' : '' }} {{ $datepicker }}" id="{{ $name }}"
            name="{{ $name }}" placeholder="{{ $label }}" {{ $readonly ? 'readonly' : '' }} {{ $disabled ? 'disabled' : '' }}
            autocomplete="off" aria-autocomplete="none" value="{{ $value }}" style="text-align: {{ $align }}">
    </div>
</div>
