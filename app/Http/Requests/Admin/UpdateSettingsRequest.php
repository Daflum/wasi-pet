<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Settings are dynamic, so we just validate that they are strings or files
        $rules = [];
        foreach ($this->all() as $key => $value) {
            if ($this->hasFile($key)) {
                $rules[$key] = 'image|max:2048';
            } else {
                $rules[$key] = 'nullable|string|max:1000';
            }
        }
        return $rules;
    }
}
