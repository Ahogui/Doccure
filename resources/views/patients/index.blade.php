@extends('admin.layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Gestion des Patients</h4>
                    <div>
                        <!-- Formulaire de recherche -->
                        <form action="{{ route('patients.index') }}" method="GET" class="form-inline mr-2">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Rechercher..."
                                       value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    @if(request('search'))
                                    <a href="{{ route('patients.index') }}" class="btn btn-outline-danger">
                                        <i class="fas fa-times"></i>
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="float-right mt-2">
                    <a href="{{ route('patients.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nouveau Patient
                    </a>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#importModal">
                        <i class="fas fa-file-import"></i> Importer
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Date création</th>
                                <th>Nom Complet</th>
                                <th>Téléphone</th>
                                <th>Sexe</th>
                                <th>Âge</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($patients as $patient)
                            <tr>
                                <td>{{ $patient->code_patient }}</td>
                                <td>{{ $patient->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $patient->nom }} {{ $patient->prenom }}</td>
                                <td>{{ $patient->telephone }}</td>
                                <td>{{ $patient->sexe == 'M' ? 'Masculin' : 'Féminin' }}</td>
                                <td>{{ $patient->age }} ans</td>
                                {{-- td>{{ $patient->date_naissance->age }} ans</td> --}}
                                <td>
                                    <a href="{{ route('patients.show', $patient) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('patients.edit', $patient) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('patients.destroy', $patient) }}" method="POST" style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Aucun patient trouvé</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $patients->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

<!-- Modal d'importation -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Importer des patients</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="importForm" action="{{ route('patients.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="import_file">Fichier Excel/CSV</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="file" name="file" required>
                            <label class="custom-file-label" for="file">Choisir un fichier</label>
                        </div>
                        <small class="form-text text-muted">
                            Taille max: 5MB. Format attendu:
                            <a href="{{ asset('storage/templates/patients_template.xlsx') }}" download>
                                <i class="fas fa-download"></i> Télécharger le modèle
                            </a>
                        </small>
                    </div>
                    <div id="importFeedback" class="alert alert-dismissible fade show d-none" role="alert">
                        <div id="feedbackContent"></div>
                        <button type="button" class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="submit" form="importForm" id="importButton" class="btn btn-primary">
                    <span id="importSpinner" class="spinner-border spinner-border-sm d-none"></span>
                    Importer
                </button>
            </div>
        </div>
    </div>
</div>

@push('page-js')
<script>
    // Afficher le nom du fichier sélectionné
    document.querySelector('.custom-file-input').addEventListener('change', function(e) {
        var fileName = document.getElementById("file").files[0].name;
        var nextSibling = e.target.nextElementSibling;
        nextSibling.innerText = fileName;
    });

    // Gestion de la recherche
    document.addEventListener('DOMContentLoaded', function() {
        // Réinitialiser la recherche
        document.querySelector('.btn-outline-danger')?.addEventListener('click', function() {
            window.location.href = "{{ route('patients.index') }}";
        });
    });
</script>

<script>
    document.getElementById('importForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const button = document.getElementById('importButton');
        const spinner = document.getElementById('importSpinner');
        const feedback = document.getElementById('importFeedback');
        const form = this;

        // Réinitialisation UI
        button.disabled = true;
        spinner.classList.remove('d-none');
        feedback.classList.remove('alert-danger', 'alert-success');
        feedback.classList.add('d-none');
        feedback.innerHTML = '';

        try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            });

            const data = await response.json();

            if (!response.ok) {
                if (data.errors) {
                    const errorList = Object.entries(data.errors)
                        .map(([field, messages]) => `${field}: ${messages.join(', ')}`)
                        .join('<br>');
                    throw new Error(`Erreurs de validation:<br>${errorList}`);
                }
                throw new Error(data.message || 'Erreur serveur');
            }

            // Succès
            feedback.classList.add('alert-success');
            feedback.innerHTML = `
                <i class="fas fa-check-circle"></i> ${data.message || 'Import réussi'}
                ${data.stats ? `<br><small>${data.stats.created} créés · ${data.stats.updated} mis à jour · ${data.stats.skipped} ignorés</small>` : ''}
            `;

            // Rechargement après 3s
            setTimeout(() => {
                window.location.reload();
            }, 3000);

        } catch (error) {
            console.error('Import error:', error);
            feedback.classList.add('alert-danger');
            feedback.innerHTML = `
                <i class="fas fa-exclamation-circle"></i>
                ${error.message || 'Une erreur inattendue est survenue'}
            `;
        } finally {
            button.disabled = false;
            spinner.classList.add('d-none');
            feedback.classList.remove('d-none');
        }
    });
</script>
@endpush
