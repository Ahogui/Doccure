@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h1>Gestion des départements</h1>

    <div class="mb-3">
        <a href="{{ route('departments.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouveau département
        </a>
    </div>

    <div class="card">
        <div class="card-body">
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
                    @foreach($departments as $department)
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
                    @endforeach
                </tbody>
            </table>

            {{ $departments->links() }}
        </div>
    </div>
</div>
@endsection
