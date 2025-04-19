@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5>Nouvelle Facture - Patient: {{ $patient->full_name }}</h5>
                </div>
                <div class="card-body">
                    <form id="invoiceForm" action="{{ route('invoices.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="patient_id" value="{{ $patient->id }}">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Département</label>
                                <select name="department_id" class="form-control select2" required>
                                    @foreach($departments as $department)
                                    <option value="{{ $department->id }}"
                                        {{ $defaultDepartment && $defaultDepartment->id == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                        @if($department->headDoctor)
                                         - Dr. {{ $department->headDoctor->name }}
                                        @endif
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Mode de Paiement</label>
                                <select name="payment_method" class="form-control" required>
                                    <option value="cash">Espèces</option>
                                    <option value="card">Carte Bancaire</option>
                                    <option value="transfer">Virement</option>
                                    <option value="insurance">Assurance</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Produits</label>
                                    <select id="productSelect" class="form-control select2">
                                        <option value="">Sélectionner un produit</option>
                                        @foreach($products as $product)
                                        <option value="{{ $product->id }}"
                                            data-price="{{ $product->price }}"
                                            data-stock="{{ $product->quantity }}">
                                            {{ $product->name }} ({{ $product->price }} FCFA)
                                            @if($product->quantity <= 5)
                                             - Stock faible: {{ $product->quantity }}
                                            @endif
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Examens</label>
                                    <select id="examSelect" class="form-control select2">
                                        <option value="">Sélectionner un examen</option>
                                        @foreach($exams as $exam)
                                        <option value="{{ $exam->id }}"
                                            data-price="{{ $exam->price }}"
                                            data-category="{{ $exam->category }}">
                                            {{ $exam->name }} ({{ $exam->formatted_price }})
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered" id="itemsTable">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="30%">Item</th>
                                        <th width="15%">Type</th>
                                        <th width="15%">Prix Unitaire</th>
                                        <th width="15%">Quantité</th>
                                        <th width="15%">Total</th>
                                        <th width="10%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Items will be added dynamically -->
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" class="text-right"><strong>Total Général:</strong></td>
                                        <td id="grandTotal">0 FCFA</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Enregistrer la Facture
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5>Informations Patient</h5>
                </div>
                <div class="card-body">
                    <p><strong>Nom:</strong> {{ $patient->full_name }}</p>
                    <p><strong>Département Actuel:</strong>
                        {{ $defaultDepartment ? $defaultDepartment->name : 'Non assigné' }}
                    </p>
                    <p><strong>Dossier Médical:</strong> {{ $patient->medical_record_number }}</p>
                    <p><strong>Assurance:</strong> {{ $patient->insurance ?? 'Non renseignée' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        height: 38px;
        padding-top: 4px;
    }
    .stock-warning {
        color: #dc3545;
        font-weight: bold;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.select2').select2();

    const items = [];
    let itemCounter = 0;

    function addItem(type, id, name, price, stock = null) {
        // Vérifier si l'item existe déjà
        const existingItem = items.find(item =>
            item.type === type && item.id === id
        );

        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            items.push({
                id,
                type,
                name,
                price,
                quantity: 1,
                stock
            });
        }

        refreshTable();
    }

    function refreshTable() {
        const tbody = $('#itemsTable tbody');
        tbody.empty();

        let grandTotal = 0;

        items.forEach((item, index) => {
            const total = item.price * item.quantity;
            grandTotal += total;

            const row = `
                <tr>
                    <td>${item.name}</td>
                    <td>${item.type === 'product' ? 'Produit' : 'Examen'}</td>
                    <td>${item.price} FCFA</td>
                    <td>
                        <input type="number"
                               name="items[${index}][quantity]"
                               value="${item.quantity}"
                               min="1"
                               max="${item.stock || ''}"
                               class="form-control qty-input"
                               data-index="${index}">
                    </td>
                    <td>${total} FCFA</td>
                    <td>
                        <input type="hidden" name="items[${index}][type]" value="${item.type}">
                        <input type="hidden" name="items[${index}][id]" value="${item.id}">
                        <button type="button" class="btn btn-danger btn-sm remove-item" data-index="${index}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;

            tbody.append(row);
        });

        $('#grandTotal').text(grandTotal.toLocaleString() + ' FCFA');
    }

    $('#productSelect').on('select2:select', function(e) {
        const data = e.params.data;
        const element = $(this).find('option[value="'+data.id+'"]');

        addItem(
            'product',
            data.id,
            data.text.split(' (')[0],
            parseFloat(element.data('price')),
            element.data('stock')
        );

        $(this).val(null).trigger('change');
    });

    $('#examSelect').on('select2:select', function(e) {
        const data = e.params.data;
        const element = $(this).find('option[value="'+data.id+'"]');

        addItem(
            'exam',
            data.id,
            data.text.split(' (')[0],
            parseFloat(element.data('price'))
        );

        $(this).val(null).trigger('change');
    });

    $(document).on('click', '.remove-item', function() {
        const index = $(this).data('index');
        items.splice(index, 1);
        refreshTable();
    });

    $(document).on('change', '.qty-input', function() {
        const index = $(this).data('index');
        const newQty = parseInt($(this).val());

        if (newQty > 0) {
            items[index].quantity = newQty;
            refreshTable();
        }
    });
});
$('#invoiceForm').on('submit', function(e) {
    e.preventDefault();

    if (items.length === 0) {
        alert('Veuillez ajouter au moins un produit ou examen');
        return;
    }

    // Ajouter les items au formulaire
    items.forEach((item, index) => {
        $(this).append(
            `<input type="hidden" name="items[${index}][type]" value="${item.type}">
             <input type="hidden" name="items[${index}][id]" value="${item.id}">
             <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}">`
        );
    });

    this.submit();
});
</script>
@endpush
