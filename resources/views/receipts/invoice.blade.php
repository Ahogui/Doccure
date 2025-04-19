<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Facture {{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; }
        .info { margin-bottom: 20px; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .items-table th, .items-table td { border: 1px solid #ddd; padding: 8px; }
        .items-table th { background-color: #f2f2f2; text-align: left; }
        .total { text-align: right; font-weight: bold; }
        .footer { margin-top: 50px; font-size: 10px; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>CENTRE MEDICO SOCIALSOEURS PASSIONNISTES</h1>
        <p>Abadjin-Doumé (route de Dabou km 17, Carrefour Institut Pasteur)</p>
        <p>Cell: +225 0789454029</p>
    </div>

    <div class="info">
        <table class="info-table">
            <tr>
                <td width="50%">
                    <strong>Facture N°:</strong> {{ $invoice->invoice_number }}<br>
                    <strong>Date:</strong> {{ $invoice->invoice_date->format('d/m/Y') }}<br>
                    <strong>Mode de paiement:</strong> {{ ucfirst($invoice->payment_method) }}
                </td>
                <td width="50%">
                    <strong>Patient:</strong> {{ $patient->full_name }}<br>
                    <strong>Dossier N°:</strong> {{ $patient->medical_record_number }}<br>
                    <strong>Assurance:</strong> {{ $patient->insurance ?? 'Non renseignée' }}
                </td>
            </tr>
        </table>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th>Description</th>
                <th>Type</th>
                <th>Prix unitaire</th>
                <th>Quantité</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>Produit</td>
                <td>{{ number_format($product->pivot->unit_price, 2, ',', ' ') }} FCFA</td>
                <td>{{ $product->pivot->quantity }}</td>
                <td>{{ number_format($product->pivot->total, 2, ',', ' ') }} FCFA</td>
            </tr>
            @endforeach

            @foreach($exams as $exam)
            <tr>
                <td>{{ $exam->name }}</td>
                <td>Examen</td>
                <td>{{ number_format($exam->pivot->price, 2, ',', ' ') }} FCFA</td>
                <td>1</td>
                <td>{{ number_format($exam->pivot->price, 2, ',', ' ') }} FCFA</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        <strong>TOTAL GENERAL: {{ number_format($invoice->grand_total, 2, ',', ' ') }} FCFA</strong>
    </div>

    <div class="footer">
        <p>Merci pour votre confiance | Facture générée le: {{ $date }}</p>
        <p>Pour toute réclamation, veuillez contacter le service client dans les 48h</p>
    </div>
</body>
</html>
