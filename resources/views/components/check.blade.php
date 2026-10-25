@props(['name', 'label', 'value' => 1, 'checked' => false])
@php($isChecked = session()->hasOldInput() ? (bool) old($name) : (bool) $checked)
<div class="form-check mb-3">
    <input type="checkbox" class="form-check-input @error($name) is-invalid @enderror" id="f-{{ $name }}"
        name="{{ $name }}" value="{{ $value }}" @checked($isChecked) {{ $attributes }}>
    <label class="form-check-label" for="f-{{ $name }}">{{ $label }}</label>
    @error($name)
    <div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>