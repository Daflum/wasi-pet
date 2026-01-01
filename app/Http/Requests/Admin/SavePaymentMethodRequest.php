<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class SavePaymentMethodRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Assuming any authenticated admin can do this
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'cci' => 'nullable|string|max:255',
            'qr_code_path' => 'nullable|image|max:2048', // Correct validation for image uploads
            'instructions' => 'nullable|string',
            'is_active' => 'required|boolean',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del banco o plataforma es obligatorio.',
            'name.string' => 'El nombre debe ser un texto.',
            'name.max' => 'El nombre no puede superar los 255 caracteres.',
            'account_number.required' => 'El número de cuenta es obligatorio.',
            'account_number.string' => 'El número de cuenta debe ser un texto.',
            'account_number.max' => 'El número de cuenta no puede superar los 255 caracteres.',
            'cci.string' => 'El CCI debe ser un texto.',
            'cci.max' => 'El CCI no puede superar los 255 caracteres.',
            'qr_code_path.image' => 'El archivo debe ser una imagen.',
            'qr_code_path.max' => 'La imagen del QR no puede pesar más de 2MB.',
            'instructions.string' => 'Las instrucciones deben ser un texto.',
        ];
    }


    /**
     * Get the validated data from the request, processing the image upload.
     *
     * @return array
     */
    public function getValidatedData(): array
    {
        $data = $this->validated();

        if ($this->hasFile('qr_code_path')) {
            // Store the new image and get its path
            $relativePath = $this->file('qr_code_path')->store('qrs', 'cloudinary');
            $url = Storage::disk('cloudinary')->url($relativePath);
            $data['qr_code_path'] = $url;

            // If updating, delete the old image
            if ($this->route('payment_method') && $this->route('payment_method')->qr_code_path) {
                // This is a simplistic way to get the public ID. A better implementation
                // might store the public ID in a separate column.
                $publicId = 'qrs/' . basename(parse_url($this->route('payment_method')->qr_code_path, PHP_URL_PATH), '.' . pathinfo(parse_url($this->route('payment_method')->qr_code_path, PHP_URL_PATH), PATHINFO_EXTENSION));
                 Cloudinary::uploadApi()->destroy($publicId);
            }
        }

        return $data;
    }
}
