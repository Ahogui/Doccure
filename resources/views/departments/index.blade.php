@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h1>Gestion des départements</h1>

    <div class="mb-3 d-flex justify-content-between">
        <a href="{{ route('departments.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouveau département
        </a>

        <!-- Formulaire de recherche -->
        <form action="{{ route('departments.index') }}" method="GET" class="form-inline">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Rechercher..."
                       value="{{ request('search') }}">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                    @if(request('search'))
                    <a href="{{ route('departments.index') }}" class="btn btn-outline-danger">
                        <i class="fas fa-times"></i>
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Responsable</th>
                            <th>Localisation</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($departments as $department)
                        <tr>
                            <td>{{ $department->name }}</td>
                            <td>{{ $department->headDoctor->full_name ?? 'Non défini' }}</td>
                            <td>{{ $department->location ?? '-' }}</td>
                            <td>
                                <a href="{{ route('departments.edit', $department->id) }}"
                                   class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('departments.destroy', $department->id) }}"
                                      method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Supprimer ce département ?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Aucun département trouvé</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $departments->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Reset la recherche quand on clique
    document.querySelector('.search-reset').addEventListener('click', function() {
        window.location.href = "{{ route('departments.index') }}";
    });
</script>
@endpush
