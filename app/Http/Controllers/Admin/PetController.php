<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Http\Requests\Admin\SavePetRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

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
    public function store(SavePetRequest $request)
    {
        Pet::create($request->getValidatedData());

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
    public function update(SavePetRequest $request, Pet $pet)
    {
        $validatedData = $request->getValidatedData();

        if (!$request->hasFile('image')) {
            $validatedData['image'] = $pet->image;
        }

        $pet->update($validatedData);

        return redirect()->route('admin.pets.index')->with('success', 'Mascota actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pet $pet)
    {
        if ($pet->image && Str::contains($pet->image, 'cloudinary')) {
            $publicId = 'pets/' . basename(parse_url($pet->image, PHP_URL_PATH), '.' . pathinfo(parse_url($pet->image, PHP_URL_PATH), PATHINFO_EXTENSION));
            Cloudinary::uploadApi()->destroy($publicId);
        }

        $pet->delete();

        return redirect()->route('admin.pets.index')->with('success', 'Mascota eliminada.');
    }

    public function updateStatus(Request $request, Pet $pet)
    {
        $request->validate(['status' => 'required|in:Disponible,En Proceso,Adoptado']);
        $pet->update(['status' => $request->status]);

        return back()->with('success', 'Estado actualizado correctamente.');
    }
}
