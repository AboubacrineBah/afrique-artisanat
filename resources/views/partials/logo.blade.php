{{-- Logo : marque textuelle + motif losange inspiré du bogolan. Variante "inverse" pour fond sombre. --}}
@php($inverse = $inverse ?? false)
<svg width="{{ $size ?? 28 }}" height="{{ $size ?? 28 }}" viewBox="0 0 28 28" aria-hidden="true" focusable="false">
    <path d="M14 2l12 12-12 12L2 14z" fill="none" stroke="{{ $inverse ? '#E6C7B0' : '#B8651B' }}" stroke-width="3" />
    <path d="M14 9l5 5-5 5-5-5z" fill="{{ $inverse ? '#FAF3E8' : '#1F3A5F' }}" />
</svg>