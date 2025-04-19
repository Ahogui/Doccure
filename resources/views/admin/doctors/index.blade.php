@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h1>Gestion des médecins</h1>

    <div class="mb-3 d-flex justify-content-between">
        <a href="{{ route('doctors.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Ajouter un médecin
        </a>

        <!-- Formulaire de recherche -->
        <form action="{{ route('doctors.index') }}" method="GET" class="form-inline">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Rechercher un médecin..."
                       value="{{ request('search') }}">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                    @if(request('search'))
                    <a href="{{ route('doctors.index') }}" class="btn btn-outline-danger">
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
                            <th>Nom complet</th>
                            <th>Spécialisation</th>
                            <th>N° Licence</th>
                            <th>Téléphone</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($doctors as $doctor)
                        <tr>
                            <td>{{ $doctor->full_name }}</td>
                            <td>{{ $doctor->specialization }}</td>
                            <td>{{ $doctor->license_number }}</td>
                            <td>{{ $doctor->phone }}</td>
                            <td>
                                <a href="{{ route('doctors.edit', $doctor->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('doctors.destroy', $doctor->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce médecin ?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Aucun médecin trouvé</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $doctors->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Script pour gérer la recherche
    document.addEventListener('DOMContentLoaded', function() {
        // Réinitialiser la recherche
        document.querySelector('.btn-outline-danger')?.addEventListener('click', function() {
            window.location.href = "{{ route('doctors.index') }}";
        });
    });
</script>
@endsection
