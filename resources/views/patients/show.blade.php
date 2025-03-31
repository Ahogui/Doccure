@extends('admin.layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Détails du Patient</h4>
                <div class="float-right">
                    <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Modifier
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Informations Personnelles</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Code Patient</th>
                                <td>{{ $patient->code_patient }}</td>
                            </tr>
                            <tr>
                                <th>Nom Complet</th>
                                <td>{{ $patient->prenom }} {{ $patient->nom }}</td>
                            </tr>
                            <tr>
                                <th>Date de Naissance</th>
                                <td>{{ $patient->date_naissance->format('d/m/Y') }} ({{ $patient->date_naissance->age }} ans)</td>
                            </tr>
                            <tr>
                                <th>Sexe</th>
                                <td>{{ $patient->sexe == 'M' ? 'Masculin' : 'Féminin' }}</td>
                            </tr>
                            <tr>
                                <th>Téléphone</th>
                                <td>{{ $patient->telephone }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $patient->email ?? 'Non renseigné' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5>Informations Médicales</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Groupe Sanguin</th>
                                <td>{{ $patient->groupe_sanguin ?? 'Non renseigné' }}</td>
                            </tr>
                            <tr>
                                <th>Date d'Enregistrement</th>
                                <td>{{ $patient->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <h5>Adresse</h5>
                        <div class="card card-body bg-light">
                            {{ $patient->adresse ?? 'Non renseignée' }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5>Allergies</h5>
                        <div class="card card-body bg-light">
                            {{ $patient->allergies ?? 'Aucune allergie connue' }}
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <h5>Antécédents Médicaux</h5>
                        <div class="card card-body bg-light">
                            {{ $patient->antecedents ?? 'Aucun antécédent médical' }}
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('patients.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour à la liste
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
