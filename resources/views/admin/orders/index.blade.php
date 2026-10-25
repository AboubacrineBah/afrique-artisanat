@extends('layouts.admin')

@section('title', 'Commandes')
@section('heading', 'Commandes')

@section('content')
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-5"><label class="visually-hidden" for="q">Rechercher</label><input id="q" class="form-control"
                type="search" name="q" value="{{ request('q') }}" placeholder="N° de commande ou nom du client…"></div>
        <div class="col-md-4"><label class="visually-hidden" for="status">Statut</label>
            <select id="status" name="status" class="form-select">
                <option value="">Tous les statuts</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status->value }}" @selected(request('status') === $status->value)>{{ $status->label() }}
                </option>@endforeach
            </select>
        </div>
        <div class="col-md-2"><button class="btn btn-outline-secondary w-100" type="submit">Filtrer</button></div>
    </form>

    <div class="panel p-2">
        <div class="table-responsive">
            <table class="table table-admin align-middle mb-0">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Client</th>
                        <th class="text-end">Articles</th>
                        <th>Statut</th>
                        <th>Paiement</th>
                        <th class="text-end">Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td class="fw-semibold">{{ $order->number }}</td>
                            <td>{{ $order->user->name }}</td>
                            <td class="text-end">{{ $order->items_count }}</td>
                            <td><span class="badge {{ $order->status->badge() }}">{{ $order->status->label() }}</span></td>
                            <td><span
                                    class="badge {{ $order->payment_status->badge() }}">{{ $order->payment_status->label() }}</span>
                            </td>
                            <td class="text-end">{{ $order->total_formatted }}</td>
                            <td class="text-end"><a class="btn btn-outline-info btn-sm" href="{{ route('admin.orders.show', $order) }}">Voir</a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-muted">Aucune commande.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3 d-flex justify-content-center">{{ $orders->links() }}</div>
@endsection