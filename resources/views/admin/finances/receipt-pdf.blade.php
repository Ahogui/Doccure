<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reçu de Caisse - {{ $data['receipt_number'] }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .title { font-size: 18px; font-weight: bold; margin: 10px 0; }
        .receipt-details { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .receipt-details th, .receipt-details td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .receipt-details th { background-color: #f2f2f2; }
        .signature {
            border-top: 1px dashed #333;
            width: 200px;
            margin: 40px auto 0;
            text-align: center;
        }
        .footer { margin-top: 50px; font-size: 12px; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $data['pharmacy'] }}</h2>
        <p>{{ $data['address'] }}</p>
        <p>Tél: {{ $data['phone'] }}</p>
        <div class="title">REÇU DE CAISSE</div>
        <p>N°: {{ $data['receipt_number'] }}</p>
    </div>

    <table class="receipt-details">
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
            <td style="font-weight: bold;">{{ $data['amount'] }}</td>
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

    <div style="text-align: center; margin-top: 30px;">
        <p>Merci pour votre confiance!</p>
        <div class="signature">
            <p>Signature & cachet</p>
        </div>
    </div>

    <div class="footer">
        <p>Imprimé le {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>