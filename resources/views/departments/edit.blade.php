@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>{{ isset($department) ? 'Modifier' : 'Créer' }} un département</h2>
        </div>
        <div class="card-body">
            <form action="{{ isset($department) ? route('departments.update', $department->id) : route('departments.store') }}" method="POST">
                @csrf
                @if(isset($department)) @method('PUT') @endif

                <div class="form-group">
                    <label for="name">Nom du département *</label>
                    <input type="text" class="form-control" id="name" name="name"
                           value="{{ $department->name ?? old('name') }}" required>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description"
                              rows="3">{{ $department->description ?? old('description') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="location">Localisation</label>
                    <input type="text" class="form-control" id="location" name="location"
                           value="{{ $department->location ?? old('location') }}">
                </div>

                <div class="form-group">
                    <label for="head_doctor_id">Médecin responsable</label>
                    <select class="form-control" id="head_doctor_id" name="head_doctor_id">
                        <option value="">-- Sélectionner --</option>
                        @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}"
                            {{ (isset($department) && $department->head_doctor_id == $doctor->id) || old('head_doctor_id') == $doctor->id ? 'selected' : '' }}>
                            {{ $doctor->full_name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">
                    {{ isset($department) ? 'Mettre à jour' : 'Créer' }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
