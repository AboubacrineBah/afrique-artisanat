@extends('layouts.admin')

@section('title', 'Clients')
@section('heading', 'Clients')

@section('content')
<div class="panel p-2"><div class="table-responsive">
    <table class="table table-admin align-middle mb-0">
        <thead><tr><th>Nom</th><th>E-mail</th><th>Téléphone</th><th class="text-end">Commandes</th><th>Inscrit le</th></tr></thead>
        <tbody>
        @forelse ($customers as $customer)
            <tr>
                <td class="fw-semibold">{{ $customer->name }}</td>
                <td>{{ $customer->email }}</td>
                <td>{{ $customer->phone ?: '—' }}</td>
                <td class="text-end">{{ $customer->orders_count }}</td>
                <td>{{ $customer->created_at->format('d/m/Y') }}</td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-muted">Aucun client.</td></tr>
        @endforelse
        </tbody>
    </table>
</div></div>
<div class="mt-3 d-flex justify-content-center">{{ $customers->links() }}</div>
@endsection
