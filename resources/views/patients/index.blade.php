@extends('admin.layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Gestion des Patients</h4>
                <div class="float-right">
                    <a href="{{ route('patients.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nouveau Patient
                    </a>
                    <!-- Bouton d'importation -->
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
                                <th>Nom Complet</th>
                                <th>Téléphone</th>
                                <th>Sexe</th>
                                <th>Âge</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($patients as $patient)
                            <tr>
                                <td>{{ $patient->code_patient }}</td>
                                <td>{{ $patient->nom_complet }}</td>
                                <td>{{ $patient->telephone }}</td>
                                <td>{{ $patient->sexe == 'M' ? 'Masculin' : 'Féminin' }}</td>
                                <td>{{ $patient->date_naissance->age }} ans</td>
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
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $patients->links() }}
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
                <form id="importForm" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="import_file">Fichier Excel/CSV</label>
                        <input type="file" class="form-control-file" id="import_file" name="file" required>
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
</script>
@endpush
@push('page-js')
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

        // Debug: Vérifiez le contenu du FormData
        for (let [key, value] of formData.entries()) {
            console.log(`${key}:`, value);
        }

        const response = await fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        });

        // Debug: Vérifiez la réponse brute
        console.log('Response status:', response.status);
        const responseText = await response.text();
        console.log('Raw response:', responseText);

        let data;
        try {
            data = JSON.parse(responseText);
        } catch (e) {
            throw new Error('Réponse serveur invalide');
        }

        if (!response.ok) {
            // Gestion améliorée des erreurs de validation
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
            ${data.stats ? `<br><small>${data.stats.created} créés · ${data.stats.skipped} ignorés</small>` : ''}
        `;

        // Rechargement doux après 3s
        setTimeout(() => {
            window.location.href = window.location.href;
        }, 3000);

    } catch (error) {
        console.error('Import error:', error);
        feedback.classList.add('alert-danger');

        // Affichage propre des erreurs
        if (error.message.includes('validation')) {
            feedback.innerHTML = `<i class="fas fa-exclamation-triangle"></i> ${error.message}`;
        } else {
            feedback.innerHTML = `
                <i class="fas fa-exclamation-circle"></i>
                ${error.message || 'Une erreur inattendue est survenue'}
            `;
        }
    } finally {
        button.disabled = false;
        spinner.classList.add('d-none');
        feedback.classList.remove('d-none');
    }
});
</script>
@endpush
