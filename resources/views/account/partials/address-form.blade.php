{{--
    Formulaire d'adresse (ajout ou modification).
    Variables : $prefix (identifiant unique), $address (Address|null), $action, $method (POST|PUT)
    Les anciennes valeurs et erreurs ne s'affichent que dans le formulaire qui a été soumis (champ caché _form).
--}}
@php
    $active = old('_form') === $prefix;
    $val = fn (string $field, $default = '') => $active ? old($field) : ($address?->{$field} ?? $default);
    $fields = [
        'label' => ['Libellé (ex. Domicile)', 'text', false],
        'recipient_name' => ['Nom du destinataire', 'text', true],
        'phone' => ['Téléphone', 'tel', true],
        'city' => ['Ville', 'text', true],
        'address_line' => ['Adresse', 'text', true],
    ];
@endphp
<form method="POST" action="{{ $action }}">
    @csrf
    @if ($method !== 'POST') @method($method) @endif
    <input type="hidden" name="_form" value="{{ $prefix }}">

    @foreach ($fields as $name => [$label, $type, $required])
        <div class="mb-3">
            <label class="form-label fw-semibold" for="{{ $prefix }}-{{ $name }}">{{ $label }}@if ($required) <span class="text-danger" aria-hidden="true">*</span>@endif</label>
            <input id="{{ $prefix }}-{{ $name }}" name="{{ $name }}" type="{{ $type }}" value="{{ $val($name) }}" @required($required)
                   class="form-control @if ($active && $errors->has($name)) is-invalid @endif">
            @if ($active) @error($name)<div class="invalid-feedback d-block">{{ $message }}</div>@enderror @endif
        </div>
    @endforeach

    <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" id="{{ $prefix }}-is_default" name="is_default" value="1" @checked($active ? (bool) old('is_default') : (bool) ($address?->is_default ?? false))>
        <label class="form-check-label" for="{{ $prefix }}-is_default">Définir comme adresse par défaut</label>
    </div>

    <button type="submit" class="btn btn-primary">Enregistrer</button>
</form>
