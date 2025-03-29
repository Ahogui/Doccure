@extends('admin.layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Détails de la Transaction</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Date</th>
                                <td>{{ $finance->transaction_date->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>Type</th>
                                <td>
                                    @if($finance->type == 'income')
                                        <span class="badge badge-success">Entrée</span>
                                    @else
                                        <span class="badge badge-danger">Dépense</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Catégorie</th>
                                <td>{{ $finance->category }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Montant</th>
                                <td class="{{ $finance->type == 'income' ? 'income' : 'expense' }}">
                                    {{ number_format($finance->amount, 0, ',', ' ') }} FCFA
                                </td>
                            </tr>
                            <tr>
                                <th>Référence</th>
                                <td>{{ $finance->reference ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Enregistré par</th>
                                <td>{{ $finance->user->name }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>Description</h5>
                            </div>
                            <div class="card-body">
                                {{ $finance->description ?? 'Aucune description' }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('finances.edit', $finance->id) }}" class="btn btn-primary">
                        <i class="fe fe-edit"></i> Modifier
                    </a>
                    <a href="{{ route('finances.index') }}" class="btn btn-secondary">
                        <i class="fe fe-arrow-left"></i> Retour
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection