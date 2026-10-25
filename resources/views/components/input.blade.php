@props(['name', 'label', 'type' => 'text', 'value' => null, 'required' => false, 'help' => null])
@php($id = 'f-' . str_replace(['[', ']', '.'], '-', $name))
<div {{ $attributes->only('class')->merge(['class' => 'mb-3']) }}>
    <label for="{{ $id }}" class="form-label fw-semibold">{{ $label }}@if ($required) <span class="text-danger"
    aria-hidden="true">*</span>@endif</label>
    <input type="{{ $type }}" id="{{ $id }}" name="{{ $name }}" @if ($type !== 'password' && $type !== 'file')
    value="{{ old($name, $value) }}" @endif @required($required) {{ $attributes->except('class')->merge(['class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')]) }}>
    @if ($help)
    <div class="form-text">{{ $help }}</div>@endif
    @error($name)
    <div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>