@extends('admin.layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">@isset($transaction) Modifier @else Ajouter @endisset une Transaction</h4>
            </div>
            <div class="card-body">
                <form action="@isset($transaction) {{ route('finances.update', $transaction->id) }} @else {{ route('finances.store') }} @endisset" method="POST">
                    @csrf
                    @isset($transaction) @method('PUT') @endisset

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date <span class="text-danger">*</span></label>
                                <input type="date" name="transaction_date" class="form-control"
                                    value="{{ old('transaction_date', $transaction->transaction_date ?? date('Y-m-d')) }}" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Type <span class="text-danger">*</span></label>
                                <select name="type" class="form-control" id="type-select" required>
                                    <option value="income" @selected(old('type', $transaction->type ?? '') == 'income')>Entrée</option>
                                    <option value="expense" @selected(old('type', $transaction->type ?? '') == 'expense')>Dépense</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Catégorie <span class="text-danger">*</span></label>
                                <select name="category" class="form-control" id="category-select" required>
                                    <option value="">Sélectionner une catégorie</option>
                                    @foreach($incomeCategories as $category)
                                        <option value="{{ $category }}"
                                            @selected(old('category', $transaction->category ?? '') == $category)
                                            class="income-category">{{ $category }}</option>
                                    @endforeach
                                    @foreach($expenseCategories as $category)
                                        <option value="{{ $category }}"
                                            @selected(old('category', $transaction->category ?? '') == $category)
                                            class="expense-category">{{ $category }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Montant (FCFA) <span class="text-danger">*</span></label>
                                <input type="number" name="amount" class="form-control" min="0" step="0.01"
                                    value="{{ old('amount', $transaction->amount ?? '') }}" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Référence</label>
                                <input type="text" name="reference" class="form-control"
                                    value="{{ old('reference', $transaction->reference ?? '') }}">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description" class="form-control" rows="3">{{ old('description', $transaction->description ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">
                            @isset($transaction) Mettre à jour @else Enregistrer @endisset
                        </button>
                        <a href="{{ route('finances.index') }}" class="btn btn-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page-js')
<script>
    $(document).ready(function() {
        // Filtrer les catégories en fonction du type sélectionné
        function filterCategories() {
            const type = $('#type-select').val();
            if (type === 'income') {
                $('.expense-category').hide();
                $('.income-category').show();
            } else {
                $('.income-category').hide();
                $('.expense-category').show();
            }

            // Réinitialiser la sélection si elle ne correspond pas au type
            const currentCategory = $('#category-select').val();
            const currentOption = $('#category-select option[value="'+currentCategory+'"]');
            if (currentOption.length && currentOption.css('display') === 'none') {
                $('#category-select').val('');
            }
        }

        // Initial filter
        filterCategories();

        // On type change
        $('#type-select').change(filterCategories);
    });
</script>
@endpush