@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Détails de la Facture #{{ $invoice->invoice_number }}</div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Informations Patient</h5>
                            <p><strong>Nom:</strong> {{ $invoice->patient->full_name }}</p>
                            <p><strong>Date:</strong> {{ $invoice->invoice_date->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-6 text-right">
                            <p><strong>Statut:</strong>
                                <span class="badge badge-{{ $invoice->status === 'paid' ? 'success' : 'warning' }}">
                                    @if($invoice->status == 'paid')
                                      Payée
                                    @elseif($invoice->status == 'unpaid')
                                        Non payée
                                    @endif
                                </span>
                            </p>
                            <p><strong>Total:</strong> {{ number_format($invoice->total_amount, 2, ',', ' ') }} FCFA</p>
                        </div>
                    </div>

                    @if($invoice->products->count() > 0)
                        <h5>Produits</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th>Quantité</th>
                                    <th>Prix Unitaire</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoice->products as $product)
                                <tr>
                                    <td>{{ $product->purchase->product }}</td>
                                    <td>{{ $product->pivot->quantity }}</td>
                                    <td>{{ number_format($product->pivot->unit_price, 2, ',', ' ') }} FCFA</td>
                                    <td>{{ number_format($product->pivot->total, 2, ',', ' ') }} FCFA</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif

                    @if($invoice->examTypes->count() > 0)
                        <h5 class="mt-4">Examens</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Examen</th>
                                    <th>Prix</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoice->examTypes as $exam)
                                <tr>
                                    <td>{{ $exam->name }}</td>
                                    <td>{{ number_format($exam->pivot->price, 2, ',', ' ') }} FCFA</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif

                    @if($invoice->products->count() === 0 && $invoice->examTypes->count() === 0)
                        <div class="alert alert-info">
                            Aucun produit ou examen associé à cette facture
                        </div>
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('invoices.download', $invoice->id) }}" class="btn btn-primary">
                            <i class="fas fa-download"></i> Télécharger le PDF
                        </a>
                        <a href="{{ route('invoices.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour à la liste
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
