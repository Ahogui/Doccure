<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor; // Ensure the Doctor model exists in this namespace
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::latest()->paginate(10);
        return view('admin.doctors.index', compact('doctors'));
    }

    public function create()
    {
        return view('admin.doctors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'license_number' => 'required|string|unique:doctors',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'address' => 'nullable|string'
        ]);

        Doctor::create($validated);

        return redirect()->route('doctors.index')->with('success', 'Médecin ajouté avec succès');
    }

    // Ajoutez les méthodes edit, update, show, destroy...
    public function edit(Doctor $doctor)
{
    return view('admin.doctors.edit', compact('doctor'));
}

public function update(Request $request, Doctor $doctor)
{
    $changes = [];

    $doctor->fill($request->all())->each(function ($value, $key) use ($doctor, &$changes) {
        if ($value !== $doctor->$key) {
            $changes[$key] = [
                'old' => $doctor->$key,
                'new' => $value
            ];
        }
    });
    if (empty($changes)) {
        return redirect()->back()->with('info', 'Aucun changement détecté');
    }

    $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'specialization' => 'required|string|max:255',
        'license_number' => [
            'required',
            'string',
            Rule::unique('doctors')->ignore($doctor->id),
        ],
        'phone' => 'nullable|string|max:20',
        'email' => 'nullable|email|max:255',
        'address' => 'nullable|string|max:500'
    ]);

    // Gestion des erreurs de numéro de licence personnalisée
    $validator = Validator::make([], []);
    if (Doctor::where('license_number', $validated['license_number'])
             ->where('id', '!=', $doctor->id)
             ->exists()) {
        $validator->errors()->add('license_number', 'Ce numéro de licence est déjà utilisé');
        return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
    }

    $doctor->update($validated);

    return redirect()->route('doctors.index')
        ->with('success', 'Médecin mis à jour avec succès')
        ->with('updated_doctor_id', $doctor->id);
}

public function destroy(Doctor $doctor)
{
    $doctor->delete();

        return redirect()->route('doctors.index')
            ->with('success', 'Transaction supprimée avec succès');

}
}
