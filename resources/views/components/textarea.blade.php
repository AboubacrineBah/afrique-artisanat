@props(['name', 'label', 'value' => null, 'rows' => 4, 'required' => false])
@php($id = 'f-' . str_replace(['[', ']', '.'], '-', $name))
<div class="mb-3">
    <label for="{{ $id }}" class="form-label fw-semibold">{{ $label }}@if ($required) <span class="text-danger"
    aria-hidden="true">*</span>@endif</label>
    <textarea id="{{ $id }}" name="{{ $name }}" rows="{{ $rows }}" @required($required) {{ $attributes->merge(['class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')]) }}>{{ old($name, $value) }}</textarea>
    @error($name)
    <div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>