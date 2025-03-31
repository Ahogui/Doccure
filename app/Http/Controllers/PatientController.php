<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use App\Imports\PatientsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::latest()->paginate(20);
        return view('patients.index', compact('patients'));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:50',
            'prenom' => 'required|string|max:50',
            'date_naissance' => 'required|date',
            'sexe' => 'required|in:M,F',
            'telephone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'groupe_sanguin' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'adresse' => 'nullable|string',
            'antecedents' => 'nullable|string',
            'allergies' => 'nullable|string'
        ]);

        Patient::create($validated);

        return redirect()->route('patients.index')
                         ->with('success', 'Patient enregistré avec succès');
    }

    public function show(Patient $patient)
    {
        return view('patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        return view('patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:50',
            'prenom' => 'required|string|max:50',
            'date_naissance' => 'required|date',
            'sexe' => 'required|in:M,F',
            'telephone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'groupe_sanguin' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'adresse' => 'nullable|string',
            'antecedents' => 'nullable|string',
            'allergies' => 'nullable|string'
        ]);

        $patient->update($validated);

        return redirect()->route('patients.show', $patient)
                         ->with('success', 'Patient mis à jour avec succès');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect()->route('patients.index')
                         ->with('success', 'Patient supprimé avec succès');
    }

    public function search(Request $request)
    {
        $term = $request->input('search');
        $patients = Patient::search($term)->paginate(20);

        return view('patients.index', compact('patients'));
    }

    // public function import(Request $request)
    // {
    //     $request->validate([
    //         'file' => 'required|mimes:xlsx,xls,csv|max:2048'
    //     ]);

    //     \Log::info('Tentative d\'importation démarrée'); // Log 1

    //     try {
    //         \Log::info('Fichier reçu:', [$request->file('file')->getClientOriginalName()]); // Log 2

    //         Excel::import(new PatientsImport, $request->file('file'));

    //         \Log::info('Importation réussie'); // Log 3

    //         return redirect()->back()
    //             ->with('success', 'Patients importés avec succès!');

    //     } catch (\Exception $e) {
    //         \Log::error('Erreur d\'importation:', ['error' => $e->getMessage()]); // Log erreur

    //         return redirect()->back()
    //             ->with('error', 'Erreur lors de l\'importation: '.$e->getMessage());
    //     }
    // }
    public function import(Request $request)
{
    $validator = Validator::make($request->all(), [
        'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:5120'
    ], [
        'file.required' => 'Aucun fichier sélectionné',
        'file.mimes' => 'Le fichier doit être de type: csv, xlsx',
        'file.max' => 'Le fichier ne doit pas dépasser 5MB'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur de validation',
            'errors' => $validator->errors()
        ], 422);
    }

    try {
        $import = new PatientsImport;
        Excel::import($import, $request->file('file'));

        return response()->json([
            'success' => true,
            'message' => 'Importation terminée avec succès',
            'stats' => [
                'created' => $import->getRowCount(),
                'skipped' => $import->getErrorCount()
            ]
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors du traitement: ' . $e->getMessage(),
            'exception' => env('APP_DEBUG') ? $e->getTrace() : null
        ], 500);
    }
}
}
