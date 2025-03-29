<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'client_id',
        'invoice_date',
        'grand_total',
        'status',
        'payment_method',
        'notes'
    ];

    protected $dates = ['invoice_date'];

    /**
     * Relation avec le client
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Relation avec les produits de la facture
     */
    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    /**
     * Génère un numéro de facture unique
     */
    public static function generateInvoiceNumber()
    {
        $latest = self::latest()->first();
        $number = $latest ? (int) explode('-', $latest->invoice_number)[1] + 1 : 1;
        return 'FACT-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Statut de la facture avec couleur
     */
    public function getStatusAttribute($value)
    {
        return [
            'paid' => 'Payé',
            'unpaid' => 'Non Payé',
            'cancelled' => 'Annulé'
        ][$value] ?? $value;
    }

    /**
     * Mode de paiement formaté
     */
    public function getPaymentMethodAttribute($value)
    {
        return [
            'cash' => 'Espèces',
            'card' => 'Carte Bancaire',
            'transfer' => 'Virement',
            'check' => 'Chèque'
        ][$value] ?? $value;
    }
}