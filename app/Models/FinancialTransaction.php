<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_date',
        'type',
        'amount',
        'category',
        'description',
        'reference',
        'user_id'
    ];

    protected $dates = ['transaction_date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Catégories prédéfinies
    public static function incomeCategories()
    {
        return [
            'Vente médicaments',
            'Vente produits para-pharmaceutiques',
            'Services',
            'Remboursements',
            'Autres revenus'
        ];
    }

    public static function expenseCategories()
    {
        return [
            'Achats médicaments',
            'Achats matériel',
            'Salaires',
            'Loyer',
            'Factures (électricité, eau)',
            'Maintenance',
            'Fournitures bureau',
            'Autres dépenses'
        ];
    }
    public function generateReceipt()
    {
        $pharmacyName = config('app.name', 'Ma Pharmacie');
        $address = config('app.address', '123 Rue de la Pharmacie, Ville');
        $phone = config('app.phone', '+123 456 7890');

        return [
            'pharmacy' => $pharmacyName,
            'address' => $address,
            'phone' => $phone,
            'receipt_number' => 'RC-' . str_pad($this->id, 6, '0', STR_PAD_LEFT),
            'date' => $this->transaction_date->format('d/m/Y H:i'),
            'transaction_type' => $this->type == 'income' ? 'ENTRÉE' : 'SORTIE',
            'category' => $this->category,
            'amount' => number_format($this->amount, 0, ',', ' ') . ' FCFA',
            'description' => $this->description,
            'reference' => $this->reference,
            'processed_by' => $this->user->name
        ];
    }
}