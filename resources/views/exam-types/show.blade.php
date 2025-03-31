@extends('admin.layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Détails du Type d'Examen</h5>
                <div class="card-tools">
                    <a href="{{ route('exam-types.edit', $examType->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Modifier
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4>Informations de base</h4>
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Nom</th>
                                <td>{{ $examType->name }}</td>
                            </tr>
                            <tr>
                                <th>Catégorie</th>
                                <td>{{ ucfirst($examType->category) }}</td>
                            </tr>
                            <tr>
                                <th>Prix</th>
                                <td>{{ number_format($examType->price, 2) }} FCFA</td>
                            </tr>
                            <tr>
                                <th>Durée estimée</th>
                                <td>{{ $examType->duration ?? 'Non spécifiée' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h4>Détails techniques</h4>
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Type d'échantillon</th>
                                <td>{{ $examType->sample_type ?? 'Non spécifié' }}</td>
                            </tr>
                            <tr>
                                <th>Date de création</th>
                                <td>{{ $examType->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Dernière modification</th>
                                <td>{{ $examType->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <h4>Description</h4>
                        <div class="card card-body bg-light">
                            {!! $examType->description ?? 'Aucune description disponible' !!}
                        </div>
                    </div>
                </div>

                @if($examType->preparation_instructions)
                <div class="row mt-4">
                    <div class="col-12">
                        <h4>Instructions de préparation</h4>
                        <div class="card card-body bg-light">
                            {!! $examType->preparation_instructions !!}
                        </div>
                    </div>
                </div>
                @endif

                <div class="mt-4">
                    <a href="{{ route('exam-types.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour à la liste
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
