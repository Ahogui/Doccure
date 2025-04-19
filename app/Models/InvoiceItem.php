<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id',
        'item_type',
        'item_id',
        'quantity',
        'unit_price',
        'total'
    ];

    public function item()
    {
        return $this->morphTo();
    }
}
