<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::with('headDoctor')->paginate(10);
        return view('departments.index', compact('departments'));
    }

    public function create()
    {
        $doctors = Doctor::all();
        return view('departments.create', compact('doctors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'head_doctor_id' => 'nullable|exists:doctors,id'
        ]);

        Department::create($validated);

        return redirect()->route('departments.index')
            ->with('success', 'Département créé avec succès');
    }

    public function edit(Department $department)
    {
        $doctors = Doctor::all();
        return view('departments.edit', compact('department', 'doctors'));
    }

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,'.$department->id,
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'head_doctor_id' => 'nullable|exists:doctors,id'
        ]);

        $department->update($validated);

        return redirect()->route('departments.index')
            ->with('success', 'Département mis à jour avec succès');
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->route('departments.index')
            ->with('success', 'Département supprimé avec succès');
    }
}
