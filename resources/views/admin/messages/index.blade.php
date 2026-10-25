@extends('layouts.admin')

@section('title', 'Messages')
@section('heading', 'Messages')

@section('content')
<div class="panel p-2"><div class="table-responsive">
    <table class="table table-admin align-middle mb-0">
        <thead><tr><th>Date</th><th>Nom</th><th>Objet</th><th>Statut</th><th class="text-end">Actions</th></tr></thead>
        <tbody>
        @forelse ($messages as $message)
            <tr @class(['fw-semibold' => $message->read_at === null])>
                <td>{{ $message->created_at->format('d/m/Y') }}</td>
                <td>{{ $message->name }}</td>
                <td>{{ $message->subject }}</td>
                <td><span class="badge {{ $message->read_at ? 'text-bg-secondary' : 'text-bg-warning' }}">{{ $message->read_at ? 'Lu' : 'Non lu' }}</span></td>
                <td class="text-end text-nowrap">
                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.messages.show', $message) }}">Lire</a>
                    <form class="d-inline" method="POST" action="{{ route('admin.messages.destroy', $message) }}" onsubmit="return confirm('Supprimer ce message ?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger" type="submit">Supprimer</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-muted">Aucun message.</td></tr>
        @endforelse
        </tbody>
    </table>
</div></div>
<div class="mt-3 d-flex justify-content-center">{{ $messages->links() }}</div>
@endsection
