@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h1>Gestion des médecins</h1>

    <div class="mb-3">
        <a href="{{ route('doctors.create') }}" class="btn btn-primary">
            Ajouter un médecin
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-striped" id="doctors-table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Spécialisation</th>
                        <th>N° Licence</th>
                        <th>Téléphone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($doctors as $doctor)
                    <tr>
                        <td>{{ $doctor->full_name }}</td>
                        <td>{{ $doctor->specialization }}</td>
                        <td>{{ $doctor->license_number }}</td>
                        <td>{{ $doctor->phone }}</td>
                        <td>
                            <a href="{{ route('doctors.edit', $doctor->id) }}" class="btn btn-sm btn-warning">
                                Modifier
                            </a>
                                <a href="{{ route('doctors.edit', $doctor->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Modifier
                                </a>
                                <form action="{{ route('doctors.destroy', $doctor->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce médecin ?')">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </button>
                                </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $doctors->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ mix('js/admin/doctors.js') }}"></script>
@endsection
