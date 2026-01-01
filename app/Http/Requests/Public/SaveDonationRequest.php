<?php

namespace App\Http\Requests\Public;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Storage;

class SaveDonationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'donor_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string',
            'proof' => 'required|image|max:2048',
            'pet_id' => 'nullable|exists:pets,id',
        ];
    }

    public function messages(): array
    {
        return [
            'donor_name.required' => 'El nombre del donante es obligatorio.',
            'amount.required' => 'El monto es obligatorio.',
            'amount.numeric' => 'El monto debe ser un número.',
            'amount.min' => 'El monto debe ser al menos 1.',
            'payment_method.required' => 'El método de pago es obligatorio.',
            'proof.required' => 'El comprobante es obligatorio.',
            'proof.image' => 'El comprobante debe ser una imagen.',
            'proof.max' => 'El comprobante no debe pesar más de 2MB.',
        ];
    }

    public function getValidatedData(): array
    {
        $data = $this->validated();

        if ($this->hasFile('proof')) {
            $relativePath = $this->file('proof')->store('donations', 'cloudinary');
            $data['proof_path'] = Storage::disk('cloudinary')->url($relativePath);
            unset($data['proof']);
        }

        return $data;
    }
}
