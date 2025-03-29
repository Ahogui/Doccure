<?php

namespace App\Http\Controllers;

use App\Models\ExamType;
use Illuminate\Http\Request;

class ExamTypeController extends Controller
{
    public function index()
    {
        $examTypes = ExamType::latest()->paginate(10);
        return view('exam-types.index', compact('examTypes'));
    }

    public function create()
    {
        $categories = ['laboratory' => 'Laboratoire', 'imaging' => 'Imagerie', 'other' => 'Autre'];
        return view('exam-types.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string',
            'duration' => 'nullable|string',
            'sample_type' => 'nullable|string',
            'preparation_instructions' => 'nullable|string'
        ]);

        ExamType::create($validated);

        return redirect()->route('exam-types.index')
                         ->with('success', 'Type d\'examen créé avec succès');
    }

    public function show(ExamType $examType)
    {
        return view('exam-types.show', compact('examType'));
    }

    public function edit(ExamType $examType)
    {
        $categories = ['laboratory' => 'Laboratoire', 'imaging' => 'Imagerie', 'other' => 'Autre'];
        return view('exam-types.edit', compact('examType', 'categories'));
    }

    public function update(Request $request, ExamType $examType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string',
            'duration' => 'nullable|string',
            'sample_type' => 'nullable|string',
            'preparation_instructions' => 'nullable|string'
        ]);

        $examType->update($validated);

        return redirect()->route('exam-types.index')
                         ->with('success', 'Type d\'examen mis à jour avec succès');
    }

    public function destroy(ExamType $examType)
    {
        $examType->delete();
        return redirect()->route('exam-types.index')
                         ->with('success', 'Type d\'examen supprimé avec succès');
    }
}