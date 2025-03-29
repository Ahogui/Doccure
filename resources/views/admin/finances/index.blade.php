@extends('admin.layouts.app')

<x-assets.datatables />

@push('page-css')
    <style>
        .income { color: #28a745; }
        .expense { color: #dc3545; }
        .summary-card {
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .income-card { background-color: #e6f7ee; border-left: 5px solid #28a745; }
        .expense-card { background-color: #fce8e8; border-left: 5px solid #dc3545; }
        .balance-card { background-color: #e6f3fb; border-left: 5px solid #17a2b8; }
    </style>
@endpush

@push('page-header')
<div class="col-sm-7 col-auto">
    <h3 class="page-title">Gestion Financière</h3>
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Tableau de Bord</a></li>
        <li class="breadcrumb-item active">Transactions</li>
    </ul>
</div>
<div class="col-sm-5 col">
    <a href="{{route('finances.create')}}" class="btn btn-primary float-right mt-2">Nouvelle Transaction</a>
</div>
@endpush

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="summary-card income-card">
            <h5>Total Entrées</h5>
            <h3>{{ number_format($transactions->where('type', 'income')->sum('amount'), 0, ',', ' ') }} FCFA</h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="summary-card expense-card">
            <h5>Total Dépenses</h5>
            <h3>{{ number_format($transactions->where('type', 'expense')->sum('amount'), 0, ',', ' ') }} FCFA</h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="summary-card balance-card">
            <h5>Solde</h5>
            <h3>{{ number_format($transactions->where('type', 'income')->sum('amount') - $transactions->where('type', 'expense')->sum('amount'), 0, ',', ' ') }} FCFA</h3>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('finances.index') }}" class="mb-4">
                    <div class="row">
                        <div class="col-md-2">
                            <select name="type" class="form-control">
                                <option value="">Tous types</option>
                                <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>Entrées</option>
                                <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Dépenses</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="category" class="form-control">
                                <option value="">Toutes catégories</option>
                                @foreach(array_merge($incomeCategories, $expenseCategories) as $category)
                                    <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="Date début">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="Date fin">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">Filtrer</button>
                            <a href="{{ route('finances.index') }}" class="btn btn-secondary">Réinitialiser</a>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table id="finance-table" class="datatable table table-striped table-bordered table-hover table-center mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Catégorie</th>
                                <th>Montant</th>
                                <th>Description</th>
                                <th class="text-center action-btn">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->transaction_date->format('d/m/Y') }}</td>
                                <td>
                                    @if($transaction->type == 'income')
                                        <span class="badge badge-success">Entrée</span>
                                    @else
                                        <span class="badge badge-danger">Dépense</span>
                                    @endif
                                </td>
                                <td>{{ $transaction->category }}</td>
                                <td class="{{ $transaction->type == 'income' ? 'income' : 'expense' }}">
                                    {{ number_format($transaction->amount, 0, ',', ' ') }} FCFA
                                </td>
                                <td>{{ Str::limit($transaction->description, 30) }}</td>
                                <td class="text-center">
                                    <div class="actions">
                                        <a href="{{ route('finances.show', $transaction->id) }}" class="btn btn-sm bg-info-light" title="Voir">
                                            <i class="fe fe-eye"></i>
                                        </a>
                                        <a href="{{ route('finances.edit', $transaction->id) }}" class="btn btn-sm bg-success-light" title="Modifier">
                                            <i class="fe fe-edit"></i>
                                        </a>
                                        <form action="{{ route('finances.destroy', $transaction->id) }}" method="POST" style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm bg-danger-light" title="Supprimer" onclick="return confirm('Êtes-vous sûr?')">
                                                <i class="fe fe-trash"></i>
                                            </button>
                                        </form>
                                        <!-- Bouton pour générer le reçu -->
                                        <a href="{{ route('finances.generate.receipt', $transaction->id) }}"
                                            class="btn btn-sm bg-primary-light" title="Générer reçu">
                                             <i class="fas fa-receipt"></i>
                                         </a>

                                         <!-- Autres boutons existants -->
                                         <a href="{{ route('finances.show', $transaction->id) }}" class="btn btn-sm bg-info-light" title="Voir">
                                             <i class="fe fe-eye"></i>
                                         </a>
                                         <!-- ... -->
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page-js')
<script>
    $(document).ready(function() {
        $('#finance-table').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excel',
                    text: 'Exporter Excel',
                    className: 'btn btn-secondary',
                    title: 'Transactions_pharmacie_' + new Date().toISOString().split('T')[0],
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'pdf',
                    text: 'Exporter PDF',
                    className: 'btn btn-secondary',
                    title: 'Transactions_pharmacie_' + new Date().toISOString().split('T')[0],
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'print',
                    text: 'Imprimer',
                    className: 'btn btn-secondary',
                    title: 'Transactions_pharmacie_' + new Date().toISOString().split('T')[0],
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                }
            ],
            order: [[0, 'desc']],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/fr-FR.json'
            }
        });
    });
</script>
@endpush