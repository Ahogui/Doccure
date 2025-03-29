<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'category',
        'duration',
        'sample_type',
        'preparation_instructions'
    ];

    protected $casts = [
        'price' => 'decimal:2'
    ];

    /**
     * Relation avec les prescriptions
     */
    public function prescriptions()
    {
        return $this->belongsToMany(Prescription::class)
                    ->withPivot('quantity', 'notes');
    }

    /**
     * Scope pour les analyses de laboratoire
     */
    public function scopeLabTests($query)
    {
        return $query->where('category', 'laboratory');
    }

    /**
     * Scope pour les examens d'imagerie
     */
    public function scopeImaging($query)
    {
        return $query->where('category', 'imaging');
    }

    /**
     * Formater le prix pour l'affichage
     */
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2) . ' FCFA';
    }
}