@props([
    'name',
    'label',
    'data',
    'key',
    'textShow',
    'selected' => '',
    'kode' => false,
    'upperCase' => false,
    'select2' => '',
])



<div class="form-group mb-3">
    <label for="exampleFormControlInput1" style="font-weight: 600" class="form-label">{{ $label }}</label>
    <select name="{{ $name }}" id="{{ $name }}" class="form-select {{ $select2 }}">
        <option value="">{{ $label }}</option>
        @foreach ($data as $d)
            <option {{ $d->$key == $selected ? 'selected' : '' }} value="{{ $d->$key }}">
                {{ $kode ? $d->$key . ' - ' : '' }}
                {{ $upperCase ? strtoupper(strtolower($d->$textShow)) : ucwords(strtolower($d->$textShow)) }}
            </option>
        @endforeach
    </select>
</div>
