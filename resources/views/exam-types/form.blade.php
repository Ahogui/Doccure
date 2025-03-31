<div class="form-group">
    <label for="name">Nom de l'examen *</label>
    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name', $examType->name ?? '') }}" required>
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="description">Description</label>
    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
              rows="3">{{ old('description', $examType->description ?? '') }}</textarea>
    @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="price">Prix (FCFA) *</label>
    <input type="number" step="0.01" name="price" id="price"
           class="form-control @error('price') is-invalid @enderror"
           value="{{ old('price', $examType->price ?? '') }}" required>
    @error('price')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="category">Catégorie *</label>
    <select name="category" id="category" class="form-control @error('category') is-invalid @enderror" required>
        <option value="">Sélectionnez une catégorie</option>
        @foreach(['laboratory' => 'Laboratoire', 'imaging' => 'Imagerie', 'other' => 'Autre'] as $key => $value)
            <option value="{{ $key }}" @selected(old('category', $examType->category ?? '') == $key)>
                {{ $value }}
            </option>
        @endforeach
    </select>
    @error('category')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="duration">Durée estimée</label>
    <input type="text" name="duration" id="duration"
           class="form-control @error('duration') is-invalid @enderror"
           value="{{ old('duration', $examType->duration ?? '') }}">
    @error('duration')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="sample_type">Type d'échantillon</label>
    <input type="text" name="sample_type" id="sample_type"
           class="form-control @error('sample_type') is-invalid @enderror"
           value="{{ old('sample_type', $examType->sample_type ?? '') }}">
    @error('sample_type')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="preparation_instructions">Instructions de préparation</label>
    <textarea name="preparation_instructions" id="preparation_instructions"
              class="form-control @error('preparation_instructions') is-invalid @enderror"
              rows="3">{{ old('preparation_instructions', $examType->preparation_instructions ?? '') }}</textarea>
    @error('preparation_instructions')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
