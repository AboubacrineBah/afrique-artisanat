@extends('layouts.admin')

@section('title', 'Artisans')
@section('heading', 'Artisans')

@section('content')
    <a class="btn btn-primary btn-sm mb-4" href="{{ route('admin.artisans.create') }}"><i class="bi bi-plus-lg"
            aria-hidden="true"></i> Nouvel artisan</a>
    <div class="panel p-2">
        <div class="table-responsive">
            <table class="table table-admin align-middle mb-0">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Région</th>
                        <th class="text-end">Produits</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($artisans as $artisan)
                        <tr>
                            <td class="fw-semibold">{{ $artisan->name }}</td>
                            <td>{{ $artisan->region }}</td>
                            <td class="text-end">{{ $artisan->products_count }}</td>
                            <td class="text-end text-nowrap">
                                <a class="btn btn-sm btn-outline-secondary"
                                    href="{{ route('admin.artisans.edit', $artisan) }}">Modifier</a>
                                <form class="d-inline" method="POST" action="{{ route('admin.artisans.destroy', $artisan) }}"
                                    onsubmit="return confirm('Supprimer cet artisan ? Ses produits seront conservés.')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-muted">Aucun artisan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection