@extends('layouts.account')

@section('title', 'Mes adresses')
@section('heading', 'Mes adresses')

@section('account')
    <div class="row row-cols-1 row-cols-md-2 g-3 mb-4">
        @forelse ($addresses as $address)
            <div class="col">
                <div class="panel h-100">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h2 class="h5 mb-0">{{ $address->label ?: 'Adresse' }}</h2>
                        @if ($address->is_default)<span class="badge text-bg-success">Par défaut</span>@endif
                    </div>
                    <p>{{ $address->recipient_name }}<br>{{ $address->address_line }}<br>{{ $address->city }}<br>{{ $address->phone }}
                    </p>
                    <div class="d-flex gap-3">
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#edit-address-{{ $address->id }}">Modifier</button>
                        <form method="POST" action="{{ route('account.addresses.destroy', $address) }}"
                            onsubmit="return confirm('Supprimer cette adresse ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm text-light">Supprimer</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="edit-address-{{ $address->id }}" tabindex="-1"
                aria-labelledby="edit-title-{{ $address->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="modal-title h5" id="edit-title-{{ $address->id }}">Modifier l’adresse</h2>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                        </div>
                        <div class="modal-body">
                            @include('account.partials.address-form', ['prefix' => 'edit-' . $address->id, 'address' => $address, 'action' => route('account.addresses.update', $address), 'method' => 'PUT'])
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info mb-0">Vous n’avez pas encore d’adresse enregistrée.</div>
            </div>
        @endforelse
    </div>

    <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#new-address"
        aria-expanded="{{ old('_form') === 'new' ? 'true' : 'false' }}" aria-controls="new-address">
        <i class="bi bi-plus-lg" aria-hidden="true"></i> Ajouter une adresse
    </button>
    <div class="collapse mt-3 @if (old('_form') === 'new') show @endif" id="new-address">
        <div class="panel">
            <h2 class="h5 mb-3">Nouvelle adresse</h2>
            @include('account.partials.address-form', ['prefix' => 'new', 'address' => null, 'action' => route('account.addresses.store'), 'method' => 'POST'])
        </div>
    </div>
@endsection

@push('scripts')
    @if (str_starts_with((string) old('_form'), 'edit-'))
        <script>
            // Rouvre la fenêtre de modification si la validation a échoué
            document.addEventListener('DOMContentLoaded', () => {
                const el = document.getElementById('{{ 'edit-address-' . substr((string) old('_form'), 5) }}');
                if (el) bootstrap.Modal.getOrCreateInstance(el).show();
            });
        </script>
    @endif
@endpush