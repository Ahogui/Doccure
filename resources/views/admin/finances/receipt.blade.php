@extends('admin.layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <div class="text-center mb-4">
                    <h2>{{ $data['pharmacy'] }}</h2>
                    <p class="mb-1">{{ $data['address'] }}</p>
                    <p>Tél: {{ $data['phone'] }}</p>
                    <h4 class="mt-3">REÇU DE CAISSE</h4>
                    <p class="mb-0">N°: {{ $data['receipt_number'] }}</p>
                </div>

                <div class="receipt-details">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Date</th>
                            <td>{{ $data['date'] }}</td>
                        </tr>
                        <tr>
                            <th>Type</th>
                            <td>{{ $data['transaction_type'] }}</td>
                        </tr>
                        <tr>
                            <th>Catégorie</th>
                            <td>{{ $data['category'] }}</td>
                        </tr>
                        <tr>
                            <th>Montant</th>
                            <td class="font-weight-bold">{{ $data['amount'] }}</td>
                        </tr>
                        @if($data['reference'])
                        <tr>
                            <th>Référence</th>
                            <td>{{ $data['reference'] }}</td>
                        </tr>
                        @endif
                        @if($data['description'])
                        <tr>
                            <th>Description</th>
                            <td>{{ $data['description'] }}</td>
                        </tr>
                        @endif
                        <tr>
                            <th>Enregistré par</th>
                            <td>{{ $data['processed_by'] }}</td>
                        </tr>
                    </table>
                </div>

                <div class="mt-4 text-center">
                    <p>Merci pour votre confiance!</p>
                    <div class="signature mt-5 pt-4">
                        <p>Signature & cachet</p>
                    </div>
                </div>

                <div class="mt-5 text-center">
                    <a href="{{ route('finances.generate.receipt', ['id' => $id, 'pdf' => true]) }}"
                       class="btn btn-primary">
                        <i class="fas fa-file-pdf"></i> Télécharger PDF
                    </a>
                    <button onclick="window.print()" class="btn btn-secondary">
                        <i class="fas fa-print"></i> Imprimer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .receipt-details {
        background: #f9f9f9;
        padding: 20px;
        border-radius: 5px;
    }
    .signature {
        border-top: 1px dashed #333;
        width: 200px;
        margin: 0 auto;
    }
    @media print {
        .card-header, .btn {
            display: none !important;
        }
        body {
            background: white !important;
        }
        .card {
            border: none !important;
        }
    }
</style>
@endpush