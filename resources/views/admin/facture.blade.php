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
    <a href="#add_invoice" data-toggle="modal" class="btn btn-primary float-right mt-2">Nouvelle Facture</a>
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
                                <th>Client</th>
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
                                <label>Client</label>
                                <select class="form-control select2" name="client_id" required>
                                    <option value="">Sélectionner un client</option>
                                    @foreach($clients as $client)
                                        <option value="{{$client->id}}">{{$client->name}}</option>
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
                                                    <option value="{{$product->id}}" data-price="{{$product->price}}">{{$product->name}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="number" name="products[0][quantity]" class="form-control quantity" min="1" value="1" required></td>
                                        <td><input type="text" name="products[0][unit_price]" class="form-control unit-price" readonly></td>
                                        <td><input type="text" name="products[0][total]" class="form-control total" readonly></td>
                                        <td><button type="button" class="btn btn-danger remove-row"><i class="fa fa-trash"></i></button></td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5">
                                            <button type="button" class="btn btn-primary add-row"><i class="fa fa-plus"></i> Ajouter Produit</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right"><strong>Total:</strong></td>
                                        <td colspan="2"><input type="text" name="grand_total" class="form-control grand-total" readonly></td>
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
                    <button type="submit" class="btn btn-primary btn-block">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /ADD Modal -->

<!-- View Invoice Modal -->
<div class="modal fade" id="view_invoice" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Détails de la Facture #<span id="invoice_number"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>Pharmacie XYZ</h6>
                        <p>123 Rue de la Pharmacie<br>
                        Ville, Pays<br>
                        Tél: +123 456 7890<br>
                        Email: contact@pharmaciexyz.com</p>
                    </div>
                    <div class="col-md-6 text-right">
                        <p><strong>Client:</strong> <span id="client_name"></span></p>
                        <p><strong>Date:</strong> <span id="invoice_date"></span></p>
                        <p><strong>Statut:</strong> <span id="invoice_status"></span></p>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Quantité</th>
                                <th>Prix Unitaire</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody id="invoice_items">

                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-right"><strong>Total:</strong></td>
                                <td><span id="invoice_total"></span></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="text-center mt-4">
                    <button class="btn btn-primary print-invoice"><i class="fa fa-print"></i> Imprimer</button>
                    <button class="btn btn-success send-invoice"><i class="fa fa-envelope"></i> Envoyer par Email</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /View Invoice Modal -->
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
                {data: 'client.name', name: 'client.name'},
                {data: 'invoice_date', name: 'invoice_date'},
                {data: 'grand_total', name: 'grand_total', render: function(data) {
                    return data + ' FCFA';
                }},
                {data: 'status', name: 'status', render: function(data) {
                    var statusClass = '';
                    if(data == 'paid') statusClass = 'paid';
                    else if(data == 'unpaid') statusClass = 'unpaid';
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
                    $('#client_name').text(response.client.name);
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
                            '<option value="{{$product->id}}" data-price="{{$product->price}}">{{$product->name}}</option>'+
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
        function calculateGrandTotal() {
            var grandTotal = 0;
            $('.total').each(function() {
                grandTotal += parseFloat($(this).val()) || 0;
            });
            $('.grand-total').val(grandTotal.toFixed(2));
        }

        // Print invoice
        $('.print-invoice').click(function() {
            window.print();
        });
    });
</script>
@endpush