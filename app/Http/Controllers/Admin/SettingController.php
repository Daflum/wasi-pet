<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use App\Models\Setting;
use App\Http\Requests\Admin\UpdateSettingsRequest;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Settings/Index', [
            'settings' => Setting::all()->pluck('value', 'key'),
            'paymentMethods' => PaymentMethod::all(),
        ]);
    }

    public function update(UpdateSettingsRequest $request)
    {
        $data = $request->except(['_token', '_method']);

        foreach ($data as $key => $value) {
            if ($request->hasFile($key)) {
                $folder = str_ends_with($key, '_qr') ? 'qrs' : 'settings';
                $relativePath = $request->file($key)->store($folder, 'cloudinary');
                $url = Storage::disk('cloudinary')->url($relativePath);
                Setting::updateOrCreate(['key' => $key], ['value' => $url]);
            } elseif (is_string($value)) { // Text fields
                $value = trim($value);
                Setting::updateOrCreate(['key' => $key], ['value' => $value === '' ? null : $value]);
            }
        }

        return redirect()->back()->with('success', 'Configuración actualizada.');
    }
}
