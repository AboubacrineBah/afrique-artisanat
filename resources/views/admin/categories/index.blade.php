@extends('layouts.admin')

@section('title', 'Catégories')
@section('heading', 'Catégories')

@section('content')
    <a class="btn btn-primary btn-sm mb-4" href="{{ route('admin.categories.create') }}"><i class="bi bi-plus-lg"
            aria-hidden="true"></i> Nouvelle catégorie</a>
    <div class="panel p-2">
        <div class="table-responsive">
            <table class="table table-admin align-middle mb-0">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Identifiant d’URL</th>
                        <th class="text-end">Produits</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td class="fw-semibold">{{ $category->name }}</td>
                            <td>{{ $category->slug }}</td>
                            <td class="text-end">{{ $category->products_count }}</td>
                            <td><span
                                    class="badge {{ $category->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $category->is_active ? 'Active' : 'Inactive' }}</span>
                            </td>
                            <td class="text-end text-nowrap">
                                <a class="btn btn-sm btn-outline-secondary"
                                    href="{{ route('admin.categories.edit', $category) }}">Modifier</a>
                                <form class="d-inline" method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                                    onsubmit="return confirm('Supprimer cette catégorie ?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-muted">Aucune catégorie.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection