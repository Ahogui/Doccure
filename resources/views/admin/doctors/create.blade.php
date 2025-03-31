@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h1>Ajouter un nouveau médecin</h1>

    <form action="{{ route('doctors.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="first_name">Prénom</label>
            <input type="text" class="form-control" id="first_name" name="first_name" required>
        </div>

        <div class="form-group">
            <label for="last_name">Nom</label>
            <input type="text" class="form-control" id="last_name" name="last_name" required>
        </div>

        <div class="form-group">
            <label for="specialization">Spécialisation</label>
            <input type="text" class="form-control" id="specialization" name="specialization" required>
        </div>

        <div class="form-group">
            <label for="license_number">Numéro de licence</label>
            <input type="text" class="form-control" id="license_number" name="license_number" required>
        </div>

        <div class="form-group">
            <label for="phone">Téléphone</label>
            <input type="text" class="form-control" id="phone" name="phone">
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email">
        </div>

        <div class="form-group">
            <label for="address">Adresse</label>
            <textarea class="form-control" id="address" name="address" rows="3"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="{{ route('doctors.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
