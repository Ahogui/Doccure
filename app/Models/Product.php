<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'purchase_id','price',
        'discount','description',
    ];

    public function purchase(){
        return $this->belongsTo(Purchase::class);
    }
    public function invoices()
    {
        return $this->belongsToMany(Invoice::class)
                    ->withPivot('quantity', 'unit_price', 'total')
                    ->withTimestamps();
    }
}
