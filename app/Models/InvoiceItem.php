<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'product_id',
        'quantity',
        'unit_price',
        'total'
    ];

    /**
     * Relation avec la facture
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Relation avec le produit
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Calcul du total pour l'item
     */
    public function calculateTotal()
    {
        return $this->quantity * $this->unit_price;
    }
}