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
    
    protected $casts = [
        'date_naissance' => 'date',
        'created_at' => 'datetime', // Déjà fait par défaut dans Laravel
        'updated_at' => 'datetime', // Déjà fait par défaut dans Laravel
    ];

    public function getAgeAttribute()
    {
        return $this->date_naissance->age;
    }
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

    public function getFullNameAttribute()
    {
        return $this->nom . ' ' . $this->prenom;
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('nom', 'like', "%$term%")
            ->orWhere('prenom', 'like', "%$term%")
            ->orWhere('code_patient', 'like', "%$term%")
            ->orWhere('telephone', 'like', "%$term%")
            ->orWhere('email', 'like', "%$term%")
            ->orWhereRaw("CONCAT(nom, ' ', prenom) LIKE ?", ["%$term%"]);
        });
    }
}
