<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'patient_id',
        'invoice_date',
        'total_amount',
        'status',
        'payment_method'
    ];

    protected $casts = [
        'invoice_date' => 'datetime',
        'total_amount' => 'decimal:2'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class)->withDefault([
            'name' => 'Patient supprimé'
        ]);
    }

    public function department()
    {
        return $this->belongsTo(Department::class)->withDefault([
            'name' => 'Département inconnu'
        ]);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function getFormattedTotalAttribute()
    {
        return number_format($this->total_amount, 2) . ' FCFA';
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function markAsPaid()
    {
        $this->update(['status' => 'paid']);
        return $this;
    }
    public function products()
    {
        return $this->belongsToMany(Product::class)
                    ->withPivot('quantity', 'unit_price', 'total')
                    ->withTimestamps();
    }
    public function examTypes()
    {
        return $this->belongsToMany(ExamType::class, 'exam_type_invoice')
                    ->withPivot('price')
                    ->withTimestamps();
    }
}
