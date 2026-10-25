@extends('layouts.account')

@section('title', 'Mes commandes')
@section('heading', 'Mes commandes')

@section('account')
    @if ($orders->isEmpty())
        <div class="panel text-center py-5">
            <p class="mb-3">Vous n’avez pas encore passé de commande.</p>
            <a class="btn btn-primary" href="{{ route('products.index') }}">Découvrir le catalogue</a>
        </div>
    @else
        <div class="table-responsive panel p-2">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th class="text-end">Total</th>
                        <th><span class="visually-hidden">Détail</span></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td class="fw-semibold">{{ $order->number }}</td>
                            <td>{{ $order->placed_at?->format('d/m/Y') }}</td>
                            <td><span class="badge {{ $order->status->badge() }}">{{ $order->status->label() }}</span></td>
                            <td class="text-end">{{ $order->total_formatted }}</td>
                            <td class="text-end"><a class="btn btn-outline-primary btn-sm"
                                    href="{{ route('account.orders.show', $order) }}">Voir</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3 d-flex justify-content-center">{{ $orders->links() }}</div>
    @endif
@endsection