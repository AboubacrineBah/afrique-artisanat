@props(['name', 'label', 'options' => [], 'value' => null, 'placeholder' => null, 'required' => false])
@php
    $id = 'f-' . str_replace(['[', ']', '.'], '-', $name);
    $current = old($name, $value);
@endphp
<div class="mb-3">
    <label for="{{ $id }}" class="form-label fw-semibold">{{ $label }}@if ($required) <span class="text-danger"
    aria-hidden="true">*</span>@endif</label>
    <select id="{{ $id }}" name="{{ $name }}" @required($required) {{ $attributes->merge(['class' => 'form-select' . ($errors->has($name) ? ' is-invalid' : '')]) }}>
        @if ($placeholder !== null)
        <option value="">{{ $placeholder }}</option>@endif
        @foreach ($options as $key => $text)
            <option value="{{ $key }}" @selected((string) $current === (string) $key)>{{ $text }}</option>
        @endforeach
    </select>
    @error($name)
    <div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>