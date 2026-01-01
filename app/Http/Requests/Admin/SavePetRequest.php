<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class SavePetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|in:dog,cat',
            'age' => 'required|integer|min:0',
            'size' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'required|in:Disponible,En Proceso,Adoptado',
            'image' => 'nullable|image|max:2048',
            'gender' => 'required|in:Macho,Hembra',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'type.required' => 'El tipo de mascota es obligatorio.',
            'age.required' => 'La edad es obligatoria.',
            'status.required' => 'El estado es obligatorio.',
            'gender.required' => 'El género es obligatorio.',
            'image.image' => 'El archivo debe ser una imagen.',
            'image.max' => 'La imagen no puede pesar más de 2MB.',
        ];
    }

    public function getValidatedData(): array
    {
        $data = $this->validated();
        $pet = $this->route('pet');

        if ($this->hasFile('image')) {
            // Delete old image from Cloudinary if exists
            if ($pet && $pet->image && Str::contains($pet->image, 'cloudinary')) {
                $publicId = 'pets/' . basename(parse_url($pet->image, PHP_URL_PATH), '.' . pathinfo(parse_url($pet->image, PHP_URL_PATH), PATHINFO_EXTENSION));
                Cloudinary::uploadApi()->destroy($publicId);
            }

            $relativePath = $this->file('image')->store('pets', 'cloudinary');
            $data['image'] = Storage::disk('cloudinary')->url($relativePath);
        }

        $data['slug'] = Str::slug($data['name']);

        return $data;
    }
}
