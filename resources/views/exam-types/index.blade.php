@extends('admin.layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Liste des Types d'Examens</h5>
                <div class="card-tools">
                    <a href="{{ route('exam-types.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Ajouter
                    </a>
                </div>
            </div>
            <div class="card-body">
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
                        @foreach($examTypes as $examType)
                        <tr>
                            <td>{{ $examType->name }}</td>
                            <td>{{ $examType->category }}</td>
                            <td>{{ $examType->formatted_price }}</td>
                            <td>{{ $examType->duration ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('exam-types.show', $examType->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('exam-types.edit', $examType->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('exam-types.destroy', $examType->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $examTypes->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
