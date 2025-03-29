<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'city',
        'country',
        'tax_number',
        'client_type' // particulier, entreprise, hôpital, etc.
    ];

    /**
     * Relation avec les factures
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Formatage du numéro de téléphone pour l'affichage
     */
    public function getFormattedPhoneAttribute()
    {
        $phone = $this->phone;
        if (strlen($phone) === 9) {
            return preg_replace('/(\d{2})(\d{3})(\d{2})(\d{2})/', '$1 $2 $3 $4', $phone);
        }
        return $phone;
    }

    /**
     * Scope pour les clients actifs (ayant au moins une facture)
     */
    public function scopeActive($query)
    {
        return $query->has('invoices');
    }

    /**
     * Recherche des clients par nom ou email
     */
    public function scopeSearch($query, $term)
    {
        return $query->where('name', 'like', '%'.$term.'%')
                    ->orWhere('email', 'like', '%'.$term.'%');
    }
}