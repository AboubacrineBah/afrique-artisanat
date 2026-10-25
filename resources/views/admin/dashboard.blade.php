@extends('layouts.admin')

@section('title', 'Tableau de bord')
@section('heading', 'Tableau de bord')

@section('content')
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="small">Commandes</div>
                <div class="value">{{ $stats['orders_total'] }}</div>
                <div class="small">{{ $stats['orders_today'] }} aujourd’hui</div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="small">En attente</div>
                <div class="value">{{ $stats['orders_pending'] }}</div><a class="small"
                    href="{{ route('admin.orders.index', ['status' => 'pending']) }}">Voir</a>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="small">Chiffre d’affaires</div>
                <div class="value fs-4">@money($stats['revenue'])</div>
                <div class="small">Hors commandes annulées</div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="small">Messages non lus</div>
                <div class="value">{{ $stats['messages_unread'] }}</div><a class="small"
                    href="{{ route('admin.messages.index') }}">Voir</a>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="small">Produits actifs</div>
                <div class="value">{{ $stats['products_active'] }}</div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="small">Clients</div>
                <div class="value">{{ $stats['customers'] }}</div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <section class="col-xl-8">
            <div class="panel mb-4">
                <h2 class="h5 mb-3">Dernières commandes</h2>
                <div class="table-responsive">
                    <table class="table table-admin align-middle mb-0">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>Client</th>
                                <th>Statut</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($latestOrders as $order)
                                <tr>
                                    <td><a href="{{ route('admin.orders.show', $order) }}">{{ $order->number }}</a></td>
                                    <td>{{ $order->user->name }}</td>
                                    <td><span class="badge {{ $order->status->badge() }}">{{ $order->status->label() }}</span>
                                    </td>
                                    <td class="text-end">{{ $order->total_formatted }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-muted">Aucune commande.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section class="col-xl-4">
            <div class="panel mb-4">
                <h2 class="h5 mb-3">Alertes de stock</h2>
                <ul class="list-unstyled mb-0">
                    @forelse ($lowStock as $product)
                        <li class="d-flex justify-content-between py-1">
                            <a href="{{ route('admin.products.edit', $product) }}">{{ $product->name }}</a>
                            <span
                                class="badge {{ $product->stock === 0 ? 'text-bg-danger' : 'text-bg-warning' }}">{{ $product->stock === 0 ? 'rupture' : $product->stock }}</span>
                        </li>
                    @empty
                        <li class="text-muted">Aucun produit en stock faible.</li>
                    @endforelse
                </ul>
            </div>

            <div class="panel">
                <h2 class="h5 mb-3">Produits les plus commandés</h2>
                <ol class="mb-0 ps-3">
                    @forelse ($topProducts as $row)
                        <li class="py-1">{{ $row->product_name }} <span class="text-muted">({{ $row->total_quantity }})</span>
                        </li>
                    @empty
                        <li class="text-muted list-unstyled ms-n3">Pas encore de ventes.</li>
                    @endforelse
                </ol>
            </div>
        </section>
    </div>
@endsection