<?php

namespace Database\Seeders;

use App\Models\ExamType;
use Illuminate\Database\Seeder;

class ExamTypeSeeder extends Seeder
{
    public function run()
    {
        $examTypes = [
            [
                'name' => 'Hémogramme complet',
                'description' => 'Analyse complète des cellules sanguines',
                'price' => 5000,
                'category' => 'laboratory',
                'duration' => '24 heures',
                'sample_type' => 'Sang veineux'
            ],
            [
                'name' => 'Glycémie à jeun',
                'description' => 'Mesure du taux de glucose sanguin',
                'price' => 2500,
                'category' => 'laboratory',
                'duration' => '4 heures',
                'sample_type' => 'Sang veineux',
                'preparation_instructions' => 'Jeûne de 8 heures requis'
            ],
            // Ajoutez d'autres examens ici...
        ];

        foreach ($examTypes as $examType) {
            ExamType::create($examType);
        }
    }
}
