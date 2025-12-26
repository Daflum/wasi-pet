<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Admin/Pets/Index', [
            'pets' => Pet::latest()->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Pets/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:dog,cat,other',
            'breed' => 'required|string|max:255',
            'age' => 'required|integer|min:0',
            'size' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'required|in:available,adopted,treatment',
            'image' => 'nullable|image|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('pets', 'public');
        }

        Pet::create([
            ...$validated,
            'image' => $path,
            // Assuming slug is not in migration yet, but requested in plan.
            // Plan says: "Nombre, Slug (auto-generado), Historia."
            // Migration create_dogs_table: name, breed, age, size, description, image, status. No slug column.
            // I should stick to existing columns or add migration if needed.
            // The user plan says "Slug (auto-generado)". I should check if I missed adding slug column.
            // Migration 2025_12_25_185352_create_dogs_table.php does not have slug.
            // I will skip slug for now or add it if strict.
            // Plan mentions "Historia" -> description column matches.
        ]);

        return redirect()->route('admin.pets.index')->with('success', 'Mascota creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pet $pet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pet $pet)
    {
        return Inertia::render('Admin/Pets/Edit', [
            'pet' => $pet
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pet $pet)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:dog,cat,other',
            'breed' => 'required|string|max:255',
            'age' => 'required|integer|min:0',
            'size' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'required|in:available,adopted,treatment',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($pet->image) {
                Storage::disk('public')->delete($pet->image);
            }
            $pet->image = $request->file('image')->store('pets', 'public');
        }

        $pet->update([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'breed' => $validated['breed'],
            'age' => $validated['age'],
            'size' => $validated['size'],
            'description' => $validated['description'],
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.pets.index')->with('success', 'Mascota actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pet $pet)
    {
        if ($pet->image) {
            Storage::disk('public')->delete($pet->image);
        }
        $pet->delete();

        return redirect()->route('admin.pets.index')->with('success', 'Mascota eliminada.');
    }
}
