@extends('admin.layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Modifier Patient: {{ $patient->prenom }} {{ $patient->nom }}</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('patients.update', $patient->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nom *</label>
                                <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror"
                                       value="{{ old('nom', $patient->nom) }}" required>
                                @error('nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Prénom *</label>
                                <input type="text" name="prenom" class="form-control @error('prenom') is-invalid @enderror"
                                       value="{{ old('prenom', $patient->prenom) }}" required>
                                @error('prenom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date de Naissance *</label>
                                <input type="date" name="date_naissance" class="form-control @error('date_naissance') is-invalid @enderror"
                                       value="{{ old('date_naissance', $patient->date_naissance->format('Y-m-d')) }}" required>
                                @error('date_naissance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Sexe *</label>
                                <select name="sexe" class="form-control @error('sexe') is-invalid @enderror" required>
                                    <option value="M" @selected(old('sexe', $patient->sexe) == 'M')>Masculin</option>
                                    <option value="F" @selected(old('sexe', $patient->sexe) == 'F')>Féminin</option>
                                </select>
                                @error('sexe')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Téléphone *</label>
                                <input type="text" name="telephone" class="form-control @error('telephone') is-invalid @enderror"
                                       value="{{ old('telephone', $patient->telephone) }}" required>
                                @error('telephone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email', $patient->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Adresse</label>
                        <textarea name="adresse" class="form-control @error('adresse') is-invalid @enderror"
                                  rows="2">{{ old('adresse', $patient->adresse) }}</textarea>
                        @error('adresse')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Groupe Sanguin</label>
                                <select name="groupe_sanguin" class="form-control @error('groupe_sanguin') is-invalid @enderror">
                                    <option value="">Sélectionner</option>
                                    <option value="A+" @selected(old('groupe_sanguin', $patient->groupe_sanguin) == 'A+')>A+</option>
                                    <option value="A-" @selected(old('groupe_sanguin', $patient->groupe_sanguin) == 'A-')>A-</option>
                                    <option value="B+" @selected(old('groupe_sanguin', $patient->groupe_sanguin) == 'B+')>B+</option>
                                    <option value="B-" @selected(old('groupe_sanguin', $patient->groupe_sanguin) == 'B-')>B-</option>
                                    <option value="AB+" @selected(old('groupe_sanguin', $patient->groupe_sanguin) == 'AB+')>AB+</option>
                                    <option value="AB-" @selected(old('groupe_sanguin', $patient->groupe_sanguin) == 'AB-')>AB-</option>
                                    <option value="O+" @selected(old('groupe_sanguin', $patient->groupe_sanguin) == 'O+')>O+</option>
                                    <option value="O-" @selected(old('groupe_sanguin', $patient->groupe_sanguin) == 'O-')>O-</option>
                                </select>
                                @error('groupe_sanguin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Antécédents Médicaux</label>
                        <textarea name="antecedents" class="form-control @error('antecedents') is-invalid @enderror"
                                  rows="3">{{ old('antecedents', $patient->antecedents) }}</textarea>
                        @error('antecedents')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Allergies Connues</label>
                        <textarea name="allergies" class="form-control @error('allergies') is-invalid @enderror"
                                  rows="3">{{ old('allergies', $patient->allergies) }}</textarea>
                        @error('allergies')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Mettre à jour
                        </button>
                        <a href="{{ route('patients.show', $patient->id) }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
