{{-- Messages flash : success, warning, error --}}
@foreach (['success' => 'success', 'warning' => 'warning', 'error' => 'danger'] as $key => $type)
    @if (session($key))
        <div class="container mt-3">
            <div class="alert alert-{{ $type }} alert-dismissible fade show mb-0" role="alert">
                {{ session($key) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        </div>
    @endif
@endforeach