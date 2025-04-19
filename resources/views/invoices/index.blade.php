@extends('admin.layouts.app')

<x-assets.datatables />

@push('page-css')
    <style>
        .invoice-status {
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 12px;
        }
        .paid {
            background-color: #d4edda;
            color: #155724;
        }
        .unpaid {
            background-color: #fff3cd;
            color: #856404;
        }
        .cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
@endpush

@push('page-header')
<div class="col-sm-7 col-auto">
    <h3 class="page-title">Gestion des Factures</h3>
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Tableau de Bord</a></li>
        <li class="breadcrumb-item active">Factures</li>
    </ul>
</div>
<div class="col-sm-5 col">
    <a
    href="#add_invoice"
     data-toggle="modal" class="btn btn-primary float-right mt-2">Nouvelle Facture</a>
</div>
@endpush

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="invoice-table" class="datatable table table-striped table-bordered table-hover table-center mb-0">
                        <thead>
                            <tr>
                                <th>N° Facture</th>
                                <th>patient</th>
                                <th>Date</th>
                                <th>Montant</th>
                                <th>Statut</th>
                                <th class="text-center action-btn">Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="add_invoice" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouvelle Facture</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form method="POST" action="{{route('invoices.store')}}">
                    @csrf
                    <div class="row form-row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Patient</label>
                                <select class="form-control select2" name="patient_id" required>
                                    <option value="">Sélectionner un patient</option>
                                    @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}">{{ $patient->full_name }}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date</label>
                                <input type="date" name="invoice_date" class="form-control" value="{{date('Y-m-d')}}" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <hr>

                            <h5>Produits</h5>
                            <table class="table table-bordered" id="product_table">
                                <thead>
                                    <tr>
                                        <th width="40%">Produit</th>
                                        <th width="15%">Quantité</th>
                                        <th width="15%">Prix Unitaire</th>
                                        <th width="15%">Total</th>
                                        <th width="15%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <select class="form-control product-select" name="products[0][product_id]" required>
                                                <option value="">Sélectionner un produit</option>
                                                @foreach($products as $product)
                                                    <option value="{{$product->id}}" data-price="{{$product->price}}">{{$product->purchase->product}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="number" name="products[0][quantity]" class="form-control quantity" min="1" value="1"></td>
                                        <td><input type="text" name="products[0][unit_price]" class="form-control unit-price" readonly></td>
                                        <td><input type="text" name="products[0][total]" class="form-control total" readonly></td>
                                        <td><button type="button" class="btn btn-danger remove-row"><i class="fa fa-trash"></i></button></td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5">
                                            <button type="button" class="btn btn-primary add-product-row"><i class="fa fa-plus"></i> Ajouter Produit</button>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>

                            <h5 class="mt-4">Examens</h5>
                            <table class="table table-bordered" id="exam_table">
                                <thead>
                                    <tr>
                                        <th width="70%">Examen</th>
                                        <th width="15%">Prix</th>
                                        <th width="15%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <select class="form-control exam-select" name="exams[0][exam_id]">
                                                <option value="">Sélectionner un examen</option>
                                                @foreach($exams as $exam)
                                                    <option value="{{$exam->id}}" data-price="{{$exam->price}}">{{$exam->name}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="text" name="exams[0][price]" class="form-control exam-price" readonly></td>
                                        <td><button type="button" class="btn btn-danger remove-exam-row"><i class="fa fa-trash"></i></button></td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3">
                                            <button type="button" class="btn btn-primary add-exam-row"><i class="fa fa-plus"></i> Ajouter Examen</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-right"><strong>Total Général:</strong></td>
                                        <td>
                                            <input type="text" name="grand_total" class="form-control grand-total" readonly>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Statut</label>
                                <select class="form-control" name="status" required>
                                    <option value="paid">Payé</option>
                                    <option value="unpaid">Non Payé</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Mode de Paiement</label>
                                <select class="form-control" name="payment_method" required>
                                    <option value="cash">Espèces</option>
                                    <option value="card">Carte Bancaire</option>
                                    <option value="transfer">Virement</option>
                                    <option value="check">Chèque</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div id="hiddenItemsContainer"></div>
                    <button type="submit" class="btn btn-primary btn-block">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /ADD Modal -->

@endsection

@push('page-js')
<script>
    $(document).ready(function() {
        var table = $('#invoice-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('invoices.index')}}",
            columns: [
                {data: 'invoice_number', name: 'invoice_number'},
                {data: 'patient.name', name: 'patient.name'},
                {data: 'invoice_date', name: 'invoice_date'},
                {data: 'grand_total', name: 'grand_total', render: function(data) {
                    return data + ' FCFA';
                }},
                {data: 'status', name: 'status', render: function(data) {
                    var statusClass = '';
                    if(data == 'paid') {statusClass = 'paid';
                        data = 'Payé';
                    }
                    else if(data == 'unpaid') {statusClass = 'unpaid';
                        data = 'Non Payé';
                    }
                    else statusClass = 'cancelled';

                    return '<span class="invoice-status '+statusClass+'">'+data.charAt(0).toUpperCase() + data.slice(1)+'</span>';
                }},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

        // View invoice details
        $('#invoice-table').on('click','.viewbtn',function (){
            $('#view_invoice').modal('show');
            var id = $(this).data('id');

            // AJAX call to get invoice details
            $.ajax({
                url: '/invoices/'+id,
                type: 'GET',
                success: function(response) {
                    $('#invoice_number').text(response.invoice_number);
                    $('#patient_name').text(response.patient.name);
                    $('#invoice_date').text(response.invoice_date);
                    $('#invoice_status').text(response.status.charAt(0).toUpperCase() + response.status.slice(1));
                    $('#invoice_total').text(response.grand_total + ' FCFA');

                    var itemsHtml = '';
                    $.each(response.items, function(index, item) {
                        itemsHtml += '<tr>'+
                            '<td>'+item.product.name+'</td>'+
                            '<td>'+item.quantity+'</td>'+
                            '<td>'+item.unit_price+' FCFA</td>'+
                            '<td>'+item.total+' FCFA</td>'+
                        '</tr>';
                    });

                    $('#invoice_items').html(itemsHtml);
                }
            });
        });

        // Add product row in invoice form
        var rowCount = 0;
        $('.add-row').click(function() {
            rowCount++;
            var newRow = '<tr>'+
                '<td>'+
                    '<select class="form-control product-select" name="products['+rowCount+'][product_id]" required>'+
                        '<option value="">Sélectionner un produit</option>'+
                        '@foreach($products as $product)'+
                            '<option value="{{$product->id}}" data-price="{{$product->price}}">{{$product->purchase->product}}</option>'+
                        '@endforeach'+
                    '</select>'+
                '</td>'+
                '<td><input type="number" name="products['+rowCount+'][quantity]" class="form-control quantity" min="1" value="1" required></td>'+
                '<td><input type="text" name="products['+rowCount+'][unit_price]" class="form-control unit-price" readonly></td>'+
                '<td><input type="text" name="products['+rowCount+'][total]" class="form-control total" readonly></td>'+
                '<td><button type="button" class="btn btn-danger remove-row"><i class="fa fa-trash"></i></button></td>'+
            '</tr>';

            $('#product_table tbody').append(newRow);
        });

        // Remove product row
        $(document).on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
            calculateGrandTotal();
        });

        // Product select change
        $(document).on('change', '.product-select', function() {
            var price = $(this).find(':selected').data('price');
            $(this).closest('tr').find('.unit-price').val(price);
            calculateRowTotal($(this).closest('tr'));
        });

        // Quantity change
        $(document).on('change', '.quantity', function() {
            calculateRowTotal($(this).closest('tr'));
        });

        // Calculate row total
        function calculateRowTotal(row) {
            var quantity = row.find('.quantity').val();
            var unitPrice = row.find('.unit-price').val();
            var total = quantity * unitPrice;
            row.find('.total').val(total);
            calculateGrandTotal();
        }

        // Calculate grand total
        // function calculateGrandTotal() {
        //     var grandTotal = 0;
        //     $('.total').each(function() {
        //         grandTotal += parseFloat($(this).val()) || 0;
        //     });
        //     $('.grand-total').val(grandTotal.toFixed(2));
        // }

        // Print invoice
        // $('.print-invoice').click(function() {
        //     window.print();
        // });
        // Variables globales
var productRowCount = 0;
var examRowCount = 0;

// Ajouter une ligne de produit
$(document).on('click', '.add-product-row', function() {
    productRowCount++;
    var newRow = `
        <tr>
            <td>
                <select class="form-control product-select" name="products[${productRowCount}][product_id]">
                    <option value="">Sélectionner un produit</option>
                    @foreach($products as $product)
                        <option value="{{$product->id}}" data-price="{{$product->price}}">{{$product->purchase->product}}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="number" name="products[${productRowCount}][quantity]" class="form-control quantity" min="1" value="1"></td>
            <td><input type="text" name="products[${productRowCount}][unit_price]" class="form-control unit-price" readonly></td>
            <td><input type="text" name="products[${productRowCount}][total]" class="form-control total" readonly></td>
            <td><button type="button" class="btn btn-danger remove-row"><i class="fa fa-trash"></i></button></td>
        </tr>
    `;
    $('#product_table tbody').append(newRow);
});

// Ajouter une ligne d'examen
$(document).on('click', '.add-exam-row', function() {
    examRowCount++;
    var newRow = `
        <tr>
            <td>
                <select class="form-control exam-select" name="exams[${examRowCount}][exam_id]">
                    <option value="">Sélectionner un examen</option>
                    @foreach($exams as $exam)
                        <option value="{{$exam->id}}" data-price="{{$exam->price}}">{{$exam->name}}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="text" name="exams[${examRowCount}][price]" class="form-control exam-price" readonly></td>
            <td><button type="button" class="btn btn-danger remove-exam-row"><i class="fa fa-trash"></i></button></td>
        </tr>
    `;
    $('#exam_table tbody').append(newRow);
});

// Gestion des sélections d'examen
$(document).on('change', '.exam-select', function() {
    var price = $(this).find(':selected').data('price');
    $(this).closest('tr').find('.exam-price').val(price);
    calculateGrandTotal();
});

// Suppression d'une ligne d'examen
$(document).on('click', '.remove-exam-row', function() {
    $(this).closest('tr').remove();
    calculateGrandTotal();
});

// Calcul du total général
function calculateGrandTotal() {
    var grandTotal = 0;

    // Calcul des produits
    $('.total').each(function() {
        grandTotal += parseFloat($(this).val()) || 0;
    });

    // Calcul des examens
    $('.exam-price').each(function() {
        grandTotal += parseFloat($(this).val()) || 0;
    });

    $('.grand-total').val(grandTotal.toFixed(2));
}

// Génération du PDF
$(document).on('click', '.print-invoice', function() {
    var invoiceId = $(this).data('id');
    window.open('/invoices/' + invoiceId + '/download', '_blank');
});
$('#invoiceForm').on('submit', function(e) {
    e.preventDefault();

    // Vérifier qu'il y a au moins un item
    if (items.length === 0) {
        Swal.fire({
            icon: 'error',
            title: 'Erreur',
            text: 'Veuillez ajouter au moins un produit ou examen',
            confirmButtonColor: '#3085d6'
        });
        return;
    }

    // Ajouter les items au formulaire
    $('#hiddenItemsContainer').empty();
    items.forEach((item, index) => {
        $('#hiddenItemsContainer').append(`
            <input type="hidden" name="items[${index}][type]" value="${item.type}">
            <input type="hidden" name="items[${index}][id]" value="${item.id}">
            <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}">
        `);
    });

    // Soumettre le formulaire
    this.submit();
});    });
</script>
@endpush
