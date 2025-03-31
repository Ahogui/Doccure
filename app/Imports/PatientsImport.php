<?php

namespace App\Imports;

use App\Models\Patient;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PatientsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    use SkipsErrors, SkipsFailures;

    private $rowCount = 0;
    private $stats = [
        'created' => 0,
        'skipped' => 0
    ];

    public function model(array $row)
{
     // Nettoyage des en-têtes
     $normalizedRow = [];
     foreach ($row as $key => $value) {
         $normalizedKey = Str::slug(strtolower($key), '_');
         $normalizedRow[$normalizedKey] = trim($value);
     }

     // Validation manuelle
     if (empty($normalizedRow['nom'])) {
         throw new \Exception("Le champ nom est requis à la ligne ".$this->getRow());
     }

     return new Patient([
         'nom' => $normalizedRow['nom'],
         'prenom' => $normalizedRow['prenom'],
         'date_naissance' => $normalizedRow['date_naissance'],
         'sexe' => strtoupper(substr($normalizedRow['sexe'], 0, 1)),
         'telephone' => $normalizedRow['telephone'],
         'email' => $normalizedRow['email'],
         'adresse' => $normalizedRow['adresse'],
         'groupe_sanguin' => $normalizedRow['groupe_sanguin'],
         'antecedents' => $normalizedRow['antecedents'],
         'allergies' => $normalizedRow['telephone'],

     ]);

}

private function cleanString(?string $value): ?string
{
    return $value ? trim(preg_replace('/\s+/', ' ', $value)) : null;
}

private function normalizeBloodGroup(?string $value): ?string
{
    $valid = ['A+','A-','B+','B-','AB+','AB-','O+','O-'];
    return in_array(strtoupper($value), $valid) ? strtoupper($value) : null;
}

    public function rules(): array
    {
        return [
            '*.nom' => 'required|string|max:50',
            '*.prenom' => 'required|string|max:50',
            '*.date_naissance' => 'required|date',
            '*.sexe' => 'required|in:M,F,m,f',
            '*.telephone' => 'required|string|max:20',
            '*.email' => 'nullable|email',
            '*.groupe_sanguin' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-,a+,a-,b+,b-,ab+,ab-,o+,o-',
        ];
    }

    function getRowCount(): int
    {
        return $this->rowCount;
    }

    public function getStats(): array
    {
        return $this->stats;
    }

    private function normalizeHeaders(array $row): array
    {
        $normalized = [];
        foreach ($row as $key => $value) {
            $normalized[strtolower(trim($key))] = $value;
        }
        return $normalized;
    }

    private function parseDate($value)
    {
        if (is_numeric($value)) {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
        }

        try {
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
    private $errorCount = 0;

    public function getErrorCount(): int
    {
        return $this->errorCount;
    }

    public function onError(\Throwable $e)
    {
        $this->errorCount++;
        logger()->error('Import error: '.$e->getMessage());
    }
}
