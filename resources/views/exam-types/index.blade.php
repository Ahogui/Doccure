@extends('admin.layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Liste des Types d'Examens</h5>
                    <div>
                        <!-- Formulaire de recherche -->
                        <form action="{{ route('exam-types.index') }}" method="GET" class="form-inline">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Rechercher..."
                                       value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    @if(request('search'))
                                    <a href="{{ route('exam-types.index') }}" class="btn btn-outline-danger">
                                        <i class="fas fa-times"></i>
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-tools">
                    <a href="{{ route('exam-types.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Ajouter
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Catégorie</th>
                                <th>Prix</th>
                                <th>Durée</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($examTypes as $examType)
                            <tr>
                                <td>{{ $examType->name }}</td>
                                <td>{{ $examType->category }}</td>
                                <td>{{ $examType->formatted_price }}</td>
                                <td>{{ $examType->duration ?? 'N/A' }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('exam-types.show', $examType->id) }}" class="btn btn-info" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('exam-types.edit', $examType->id) }}" class="btn btn-primary" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('exam-types.destroy', $examType->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce type d'examen ?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Aucun type d'examen trouvé</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $examTypes->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
