<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'code_patient',
        'nom',
        'prenom',
        'date_naissance',
        'sexe',
        'adresse',
        'telephone',
        'email',
        'groupe_sanguin',
        'antecedents',
        'allergies'
    ];

    protected $dates = ['date_naissance'];

    // Relation avec les consultations
    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }

    // Relation avec les ordonnances
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    // Génération automatique du code patient
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($patient) {
            $patient->code_patient = 'PAT-' . str_pad(Patient::count() + 1, 6, '0', STR_PAD_LEFT);
        });
    }

    // Accessor pour le nom complet
    public function getNomCompletAttribute()
    {
        return $this->prenom . ' ' . $this->nom;
    }

    // Scope pour la recherche
    public function scopeSearch($query, $term)
    {
        return $query->where('nom', 'like', "%$term%")
                    ->orWhere('prenom', 'like', "%$term%")
                    ->orWhere('code_patient', 'like', "%$term%")
                    ->orWhere('telephone', 'like', "%$term%");
    }
}
